<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Contrato;
use App\Models\Empleado;
use App\Models\Cuestionario;
use App\Models\EncuestaAsignada;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader; // Asegúrate de tener 'league/csv' instalado
use League\Csv\Statement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarLinkEncuestaMailable; // Asegúrate de que este Mailable exista
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Nom035Controller extends Controller
{
    /** Constructor */
    public function __construct() { }

    // --- Métodos de Dashboard y Carga de Datos ---
    public function index()
    {
        $viewName = 'admin.nom035.index';
        if (!view()->exists($viewName)) { Log::error("Vista '$viewName' no encontrada."); abort(404, "Vista no encontrada."); }
        try {
            $nom035CuestionarioIds = Cuestionario::whereIn('codigo', ['NOM035_G1', 'NOM035_G2', 'NOM035_G3'])
                ->pluck('id')->toArray();

            if(empty($nom035CuestionarioIds)) Log::warning('No se encontraron Cuestionarios NOM035 en la base de datos. Verifica el Seeder.');

            $totalEncuestasAsignadas = EncuestaAsignada::whereIn('cuestionario_id', $nom035CuestionarioIds)->count();

            // Monitores de Progreso (Monitoreo de estado y riesgo)
            $monitores = [
                // Progreso por Estado
                'progreso_estado' => EncuestaAsignada::whereIn('cuestionario_id', $nom035CuestionarioIds)
                    ->select('estado', DB::raw('count(*) as count'))
                    ->groupBy('estado')
                    ->pluck('count', 'estado'),

                // Monitoreo de Riesgo (Placeholder: solo cuenta resultados finales completados)
                'monitoreo_riesgo' => [
                    'completadas' => EncuestaAsignada::whereIn('cuestionario_id', $nom035CuestionarioIds)
                        ->where('estado', 'completado')->count(),
                    'en_revision' => 0, // En un sistema real, se usaría una tabla de resultados para esto
                ],
            ];

            // Historial Reciente por Encuesta
            $cuestionariosMaestros = Cuestionario::whereIn('id', $nom035CuestionarioIds)->get();
            $historialRespuestas = [];

            foreach ($cuestionariosMaestros as $cuestionario) {
                $historialRespuestas[$cuestionario->nombre] = EncuestaAsignada::where('cuestionario_id', $cuestionario->id)
                    ->where('estado', 'completado')
                    ->with('empleado.empresa') // Cargar empresa para mostrar de dónde vino
                    ->latest('fecha_completado')
                    ->take(5) // Últimas 5 personas
                    ->get();
            }

            // Paginar las empresas que tienen al menos un contrato con cuestionarios NOM035
            $empresasNom035 = Empresa::whereHas('contratos.cuestionarios', function ($query) use ($nom035CuestionarioIds) {
                $query->whereIn('cuestionarios.id', $nom035CuestionarioIds);
            })
                ->withCount(['contratos as contratos_nom035_count' => function ($query) use ($nom035CuestionarioIds) {
                    $query->whereHas('cuestionarios', function ($q) use ($nom035CuestionarioIds) { $q->whereIn('cuestionarios.id', $nom035CuestionarioIds); });
                }])
                ->orderBy('nombre')
                ->paginate(10);

            // Contar el total de contratos NOM035
            $totalContratosNom035 = Contrato::whereHas('cuestionarios', function ($q) use ($nom035CuestionarioIds) {
                $q->whereIn('cuestionarios.id', $nom035CuestionarioIds);
            })
                ->count();

        } catch (QueryException $e) {
            Log::error('Error de BD cargando dashboard NOM035: ' . $e->getMessage());
            session()->flash('error', 'Error al cargar datos de empresas. Verifica la conexión a la base de datos.');
            $empresasNom035 = new LengthAwarePaginator([], 0, 10);
            $totalContratosNom035 = 'Error';
            $monitores = ['progreso_estado' => collect(), 'monitoreo_riesgo' => ['completadas' => 0, 'en_revision' => 0]];
            $historialRespuestas = [];
        } catch (\Exception $e) {
            Log::error('Error general cargando dashboard NOM035: ' . $e->getMessage());
            session()->flash('error', 'No se pudieron cargar los datos del módulo NOM-035.');
            $empresasNom035 = new LengthAwarePaginator([], 0, 10);
            $totalContratosNom035 = 'Error';
            $monitores = ['progreso_estado' => collect(), 'monitoreo_riesgo' => ['completadas' => 0, 'en_revision' => 0]];
            $historialRespuestas = [];
        }

        // Pasar los datos a la vista
        return view($viewName, compact('empresasNom035', 'totalContratosNom035', 'monitores', 'totalEncuestasAsignadas', 'historialRespuestas'));
    }

    // --- MONITOR DE CONTRATOS GENERAL ---
    public function showContratosMonitor()
    {
        $viewName = 'admin.nom035.monitoreo.index';
        if (!view()->exists($viewName)) { Log::error("Vista '$viewName' no encontrada."); abort(404, "Vista no encontrada."); }

        try {
            $nom035CuestionarioIds = Cuestionario::whereIn('codigo', ['NOM035_G1', 'NOM035_G2', 'NOM035_G3'])->pluck('id')->toArray();

            // Obtener todos los contratos activos que tienen cuestionarios NOM-035
            $contratos = Contrato::with('empresa:id,nombre')
                ->where('estado', 'activo')
                ->whereHas('cuestionarios', function ($q) use ($nom035CuestionarioIds) {
                    $q->whereIn('cuestionarios.id', $nom035CuestionarioIds);
                })
                ->withCount(['encuestasAsignadas as total_asignadas' => function ($query) {
                    $query->whereIn('estado', ['pendiente', 'en_progreso', 'completado']);
                }])
                ->withCount(['encuestasAsignadas as total_completadas' => function ($query) {
                    $query->where('estado', 'completado');
                }])
                ->get();

        } catch (\Exception $e) {
            Log::error('Error al cargar datos para monitor de contratos: ' . $e->getMessage());
            session()->flash('error', 'Error al cargar la lista de contratos para monitoreo.');
            $contratos = collect();
        }

        return view($viewName, compact('contratos'));
    }

    // --- MONITOR DE EMPLEADOS ---
    public function showMonitorEmpleados(Empresa $empresa)
    {
        $viewName = 'admin.nom035.monitoreo.empleados';
        if (!view()->exists($viewName)) { abort(404, "Vista de Monitoreo de Empleados no encontrada."); }

        try {
            // Cargar todos los empleados de la empresa con sus encuestas asignadas
            $empleados = $empresa->empleados()
                ->with(['encuestasAsignadas' => function ($query) {
                    $query->with(['contrato:id,nombre', 'cuestionario:id,nombre,codigo'])
                        ->orderBy('fecha_asignacion', 'desc');
                }])
                ->orderBy('apellido_paterno')
                ->get();

        } catch (\Exception $e) {
            Log::error('Error al cargar empleados para monitoreo (Empresa ID: ' . $empresa->id . '): ' . $e->getMessage());
            session()->flash('error', 'No se pudieron cargar los datos de los empleados para el monitor.');
            $empleados = collect(); // Enviar colección vacía en caso de error
        }

        return view($viewName, compact('empresa', 'empleados'));
    }

    // --- MONITOR DE EMAIL ---
    public function showMonitorEmail(Empresa $empresa)
    {
        $viewName = 'admin.nom035.monitoreo.email';
        if (!view()->exists($viewName)) { abort(404, "Vista de Monitor de Email no encontrada."); }
        // Aquí se cargaría la lista de encuestas asignadas de esa empresa para reenviar/personalizar
        return view($viewName, compact('empresa'));
    }

    // --- MONITOR DE REPORTES ---
    public function showMonitorReportes(Empresa $empresa)
    {
        $viewName = 'admin.nom035.monitoreo.reportes';
        if (!view()->exists($viewName)) { abort(404, "Vista de Monitor de Reportes no encontrada."); }
        // Aquí se mostrarían las opciones de descarga de reportes por empresa (PDF/Excel)
        return view($viewName, compact('empresa'));
    }


    // --- Gestión de Empresas ---

    /** Muestra el formulario para crear una nueva empresa. */
    public function createEmpresa()
    {
        $viewName = 'admin.nom035.empresas.create';
        if (!view()->exists($viewName)) { abort(404, "Vista '$viewName' no encontrada."); }
        return view($viewName);
    }

    /** Guarda una nueva empresa, incluyendo logo y actividad principal. */
    public function storeEmpresa(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'razon_social' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:13|unique:empresas,rfc',
            'direccion' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'actividad_principal' => 'nullable|string|max:255',
        ]);
        $logoPath = null;
        try {
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $logoName = time().'_'.uniqid().'.'.$request->file('logo')->getClientOriginalExtension();
                $logoPath = $request->file('logo')->storeAs('logos', $logoName, 'public');
            }
            $dataToCreate = $validated;
            if ($logoPath) { $dataToCreate['logo_path'] = $logoPath; }
            else { unset($dataToCreate['logo']); }
            Empresa::create($dataToCreate);
            Log::info('Nueva empresa creada (NOM035)', ['nombre' => $validated['nombre'], 'user_id' => Auth::id(), 'ip' => $request->ip()]);
            return redirect()->route('nom035.index')->with('status', 'Empresa "'.$validated['nombre'].'" creada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            if ($logoPath && Storage::disk('public')->exists($logoPath)) { Storage::disk('public')->delete($logoPath); }
            return back()->with('error', 'No se pudo guardar la empresa. Verifica los datos (el RFC podría estar duplicado).')->withInput();
        } catch (\Exception $e) {
            if ($logoPath && Storage::disk('public')->exists($logoPath)) { Storage::disk('public')->delete($logoPath); }
            return back()->with('error', 'Ocurrió un error inesperado al guardar la empresa.')->withInput();
        }
    }

    // --- Gestión de Contratos ---

    /** Muestra la página para listar y crear contratos. */
    public function createContrato()
    {
        $viewName = 'admin.nom035.contratos.create_list';
        if (!view()->exists($viewName)) { abort(404, "Vista '$viewName' no encontrada."); }

        $empresasConContratos = collect();
        $empresasParaSelect = collect();
        $cuestionariosParaSelect = collect();

        try {
            $nom035CuestionarioIds = Cuestionario::whereIn('codigo', ['NOM035_G1', 'NOM035_G2', 'NOM035_G3'])->pluck('id')->toArray();
            $empresasConContratos = Empresa::with(['contratos' => function($query) use ($nom035CuestionarioIds) {
                $query->whereHas('cuestionarios', function($q) use ($nom035CuestionarioIds){
                    $q->whereIn('cuestionarios.id', $nom035CuestionarioIds);
                })->with('cuestionarios:id,codigo,nombre')->orderBy('fecha_inicio', 'desc');
            }])
                ->whereHas('contratos.cuestionarios', function ($query) use ($nom035CuestionarioIds) {
                    $query->whereIn('cuestionarios.id', $nom035CuestionarioIds);
                })->orderBy('nombre')->get();

            $empresasParaSelect = Empresa::orderBy('nombre')->pluck('nombre', 'id');
            $cuestionariosParaSelect = Cuestionario::whereIn('codigo', ['NOM035_G1', 'NOM035_G2', 'NOM035_G3'])
                ->orderBy('nombre')->pluck('nombre', 'id');

        } catch (\Exception $e) {
            return redirect()->route('nom035.index')->with('error', 'No se pudieron cargar los datos para gestionar contratos.');
        }
        return view($viewName, compact('empresasConContratos', 'empresasParaSelect', 'cuestionariosParaSelect'));
    }

    /** Guarda un nuevo contrato y lo asocia a los cuestionarios seleccionados. */
    public function storeContrato(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:2000',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string|in:activo,completado,cancelado',
            'cuestionarios' => 'required|array|min:1',
            'cuestionarios.*' => 'exists:cuestionarios,id',
        ], [ /* ... mensajes ... */ ]);
        try {
            $contrato = Contrato::create(collect($validated)->except('cuestionarios')->all());
            $contrato->cuestionarios()->attach($validated['cuestionarios']);
            return redirect()->route('nom035.contratos.create')->with('status', 'Contrato "'.$validated['nombre'].'" creado y asignado.');
        } catch (QueryException $e) {
            return back()->with('error', 'No se pudo guardar el contrato. Verifica los datos.')->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error inesperado al guardar el contrato.')->withInput();
        }
    }

    // --- Gestión de Empleados ---

    /** Muestra la página principal de gestión de empleados. */
    public function indexEmpleados(Request $request)
    {
        $viewName = 'admin.nom035.empleados.index';
        if (!view()->exists($viewName)) { abort(404, "Vista '$viewName' no encontrada."); }
        try {
            $empresas = Empresa::orderBy('nombre')->pluck('nombre', 'id');
            $empleados = collect();
        } catch (\Exception $e) {
            return redirect()->route('nom035.index')->with('error', 'No se pudieron cargar los datos necesarios para gestionar empleados.');
        }
        return view($viewName, compact('empresas', 'empleados'));
    }

    /** Muestra el formulario para agregar un empleado individualmente. */
    public function createEmpleado()
    {
        $viewName = 'admin.nom035.empleados.create';
        if (!view()->exists($viewName)) { abort(404); }
        try {
            $empresas = Empresa::orderBy('nombre')->pluck('nombre', 'id');
        } catch (\Exception $e) {
            return redirect()->route('nom035.empleados.index')->with('error', 'No se pudieron cargar las empresas.');
        }
        return view($viewName, compact('empresas'));
    }


    /** Guarda un nuevo empleado agregado individualmente. */
    public function storeEmpleado(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'clave' => [
                'nullable', 'string', 'max:100',
                Rule::unique('empleados')->where(function ($query) use ($request) {
                    return $query->where('empresa_id', $request->input('empresa_id'));
                })
            ],
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'puesto' => 'nullable|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'centro_trabajo' => 'nullable|string|max:255',
            'fecha_ingreso' => 'nullable|date',
        ], [
            'clave.unique' => 'La clave de empleado ya existe para esta empresa.'
        ]);

        try {
            $empleado = Empleado::create($validated);
            Log::info('Nuevo empleado agregado (NOM035)', ['nombre' => $empleado->nombre_completo, 'user_id' => Auth::id(), 'ip' => $request->ip()]);
            return redirect()->route('nom035.empleados.index')->with('status', 'Empleado "'.$empleado->nombre_completo.'" agregado.');
        } catch (QueryException $e) {
            return redirect()->route('nom035.empleados.index')->with('error', 'No se pudo guardar el empleado. (clave duplicada).')->withInput();
        } catch (\Exception $e) {
            return redirect()->route('nom035.empleados.index')->with('error', 'Ocurrió un error inesperado.')->withInput();
        }
    }

    /** Maneja la subida masiva de empleados desde un archivo CSV. */
    public function uploadEmpleados(Request $request)
    {
        $request->validate([
            'empresa_id_upload' => 'required|exists:empresas,id',
            'archivo_empleados' => 'required|file|mimes:csv,txt|max:5120',
        ], [ /* ... mensajes ... */ ]);

        $empresaId = $request->input('empresa_id_upload');
        $file = $request->file('archivo_empleados');
        $empleadosInsertados = 0;
        $empleadosFallidos = [];
        $existingClaves = Empleado::where('empresa_id', $empresaId)->whereNotNull('clave')->pluck('clave')->flip()->toArray();

        try {
            $content = file_get_contents($file->getPathname());
            if (strpos($content, "\xEF\xBB\xBF") === 0) { $content = substr($content, 3); }
            else { if (!mb_check_encoding($content, 'UTF-8')) { $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1'); } }
            $delimiter = ',';
            $firstLine = strtok($content, "\n");
            if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) { $delimiter = ';'; }
            $csv = Reader::createFromString($content);
            $csv->setDelimiter($delimiter);
            $csv->setHeaderOffset(0);
            $header = collect($csv->getHeader())->map(function($h) { $h = trim(strtolower($h)); $h = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $h); $h = preg_replace('/[^a-z0-9_]/', '', $h); return $h; })->toArray();
            $columnMap = [
                'clave' => ['clave', 'idempleado', 'employeeid', 'codigo'],
                'nombre' => ['nombre', 'nombres', 'firstname', 'primernombre'],
                'apellido_paterno' => ['apellidopaterno', 'apellido_paterno', 'apellidopat', 'lastname', 'primerapellido'],
                'apellido_materno' => ['apellidomaterno', 'apellido_materno', 'apellidomat', 'middlename', 'segundoapellido'],
                'email' => ['email', 'correo', 'correoelectronico'],
                'puesto' => ['puesto', 'cargo', 'position', 'jobtitle'],
                'departamento' => ['departamento', 'area', 'department'],
                'centro_trabajo' => ['centrotrabajo', 'centro_trabajo', 'centrodetrabajo', 'workcenter', 'location'],
                'fecha_ingreso' => ['fechaingreso', 'fecha_ingreso', 'fechadeingreso', 'hiredate', 'startdate'],
            ];
            $headerMapping = [];
            $originalHeaderNames = $csv->getHeader();
            foreach ($columnMap as $dbField => $possibleHeaders) {
                foreach ($possibleHeaders as $possibleHeader) {
                    $index = array_search($possibleHeader, $header);
                    if ($index !== false) { $headerMapping[$dbField] = $originalHeaderNames[$index]; break; }
                }
            }
            if (!isset($headerMapping['nombre']) || !isset($headerMapping['apellido_paterno'])) {
                return redirect()->route('nom035.empleados.index')->with('error', 'El archivo CSV debe contener "nombre" y "apellido_paterno".');
            }
            $records = Statement::create()->process($csv);
            $rowIndex = 1;
            foreach ($records as $record) {
                $rowIndex++;
                $data = ['empresa_id' => $empresaId];
                $originalRowData = [];
                foreach ($headerMapping as $dbField => $csvHeaderName) {
                    $value = isset($record[$csvHeaderName]) ? trim($record[$csvHeaderName]) : null;
                    $data[$dbField] = $value;
                    $originalRowData[$csvHeaderName] = $value;
                }
                if (!empty($data['fecha_ingreso'])) {
                    try { $date = Carbon::parse(str_replace('/', '-', $data['fecha_ingreso'])); $data['fecha_ingreso'] = $date->format('Y-m-d'); }
                    catch (\Exception $e) { $data['fecha_ingreso'] = 'formato_invalido'; }
                } else { $data['fecha_ingreso'] = null; }
                $validator = Validator::make($data, [
                    'empresa_id' => 'required|exists:empresas,id',
                    'clave' => 'nullable|string|max:100',
                    'nombre' => 'required|string|max:255',
                    'apellido_paterno' => 'required|string|max:255',
                    'apellido_materno' => 'nullable|string|max:255',
                    'email' => 'nullable|email|max:255',
                    'puesto' => 'nullable|string|max:255',
                    'departamento' => 'nullable|string|max:255',
                    'centro_trabajo' => 'nullable|string|max:255',
                    'fecha_ingreso' => 'nullable|date_format:Y-m-d',
                ]);
                $rowErrors = [];
                if ($validator->fails()) { $rowErrors = $validator->errors()->all(); }
                if(!empty($data['clave']) && isset($existingClaves[$data['clave']])) { $rowErrors[] = 'La clave de empleado "'.$data['clave'].'" ya existe.'; }
                if (!empty($rowErrors)) { $empleadosFallidos[] = ['fila' => $rowIndex, 'datos' => $originalRowData, 'errores' => $rowErrors]; continue; }
                try {
                    Empleado::create($validator->validated());
                    $empleadosInsertados++;
                    if(!empty($data['clave'])) { $existingClaves[$data['clave']] = true; }
                } catch (QueryException $e) { $empleadosFallidos[] = ['fila' => $rowIndex, 'datos' => $originalRowData, 'errores' => ['Error BD.']]; }
                catch (\Exception $e) { $empleadosFallidos[] = ['fila' => $rowIndex, 'datos' => $originalRowData, 'errores' => ['Error inesperado.']]; }
            }
            Log::info('Subida masiva completada (NOM035)', ['empresa_id' => $empresaId, 'insertados' => $empleadosInsertados, 'fallidos' => count($empleadosFallidos), 'user_id' => Auth::id(), 'ip' => $request->ip()]);
            $statusMessage = "Proceso de carga completado: {$empleadosInsertados} empleados creados.";
            $redirect = redirect()->route('nom035.empleados.index')->with('status', $statusMessage);
            if (!empty($empleadosFallidos)) {
                $warningMessage = count($empleadosFallidos) . " empleado(s) no pudieron ser procesados.";
                $redirect->with('empleados_fallidos', $empleadosFallidos)->with('warning', $warningMessage);
            }
            return $redirect;
        } catch (\League\Csv\Exception $e) {
            return redirect()->route('nom035.empleados.index')->with('error', 'Error al leer el archivo CSV: '.$e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('nom035.empleados.index')->with('error', 'Ocurrió un error inesperado al procesar el archivo.');
        }
    }


    // --- Gestión de Asignación de Encuestas ---
    public function showAssignForm(Request $request)
    {
        $viewName = 'admin.nom035.asignaciones.create';
        if (!view()->exists($viewName)) { abort(404, "Vista '$viewName' no encontrada."); }
        try {
            $selectedContratoId = $request->query('contrato_id');
            $selectedEmpresaId = $request->query('empresa_id');
            $empresas = Empresa::orderBy('nombre')->pluck('nombre', 'id');
            $contratos = collect();
            $empleados = collect();
            $cuestionarios = collect();
            $contratoSeleccionado = null;
            $nom035CuestionarioIds = Cuestionario::whereIn('codigo', ['NOM035_G1', 'NOM035_G2', 'NOM035_G3'])->pluck('id')->toArray();
            if ($selectedEmpresaId) {
                $contratos = Contrato::where('empresa_id', $selectedEmpresaId)
                    ->where('estado', 'activo')
                    ->whereHas('cuestionarios', function ($query) use ($nom035CuestionarioIds) {
                        $query->whereIn('cuestionarios.id', $nom035CuestionarioIds);
                    })
                    ->orderBy('nombre')
                    ->pluck('nombre', 'id');
            }
            if ($selectedContratoId) {
                $contratoSeleccionado = Contrato::with(['empresa', 'cuestionarios'])
                    ->find($selectedContratoId);
                if ($contratoSeleccionado) {
                    $selectedEmpresaId = $contratoSeleccionado->empresa_id;
                    $cuestionarios = $contratoSeleccionado->cuestionarios()->orderBy('nombre')->get(['cuestionarios.id', 'cuestionarios.nombre', 'cuestionarios.codigo']);
                    $cuestionarioIdsEnContrato = $cuestionarios->pluck('id')->toArray();
                    $empleados = Empleado::where('empresa_id', $contratoSeleccionado->empresa_id)
                        ->whereDoesntHave('encuestasAsignadas', function ($query) use ($selectedContratoId, $cuestionarioIdsEnContrato) {
                            $query->where('contrato_id', $selectedContratoId)
                                ->whereIn('cuestionario_id', $cuestionarioIdsEnContrato)
                                ->whereIn('estado', ['pendiente', 'en_progreso']);
                        })
                        ->orderBy('apellido_paterno')->orderBy('nombre')
                        ->get(['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'clave']);
                } else { $selectedContratoId = null; }
            }
        } catch (\Exception $e) {
            Log::error('Error al cargar datos para formulario de asignación (NOM035): ' . $e->getMessage());
            return redirect()->route('nom035.index')->with('error', 'No se pudieron cargar los datos para asignar encuestas.');
        }
        return view($viewName, compact('empresas', 'contratos', 'empleados', 'cuestionarios', 'selectedContratoId', 'contratoSeleccionado', 'selectedEmpresaId'));
    }

    /** Procesa la asignación de encuestas a los empleados seleccionados. */
    public function storeAssignment(Request $request)
    {
        $validated = $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'cuestionario_ids' => 'required|array|min:1', // Múltiples cuestionarios
            'cuestionario_ids.*' => 'required|exists:cuestionarios,id',
            'empleado_ids' => 'required|array|min:1',
            'empleado_ids.*' => 'required|exists:empleados,id',
        ]);
        $contrato = Contrato::find($validated['contrato_id']);
        $cuestionariosAAsignar = Cuestionario::whereIn('id', $validated['cuestionario_ids'])->get();
        $empleadosAAsignar = Empleado::whereIn('id', $validated['empleado_ids'])->get();

        $asignadosCount = 0;
        $fallidos = [];

        foreach ($empleadosAAsignar as $empleado) {
            foreach ($cuestionariosAAsignar as $cuestionario) {

                if (!$contrato || !$contrato->cuestionarios()->where('cuestionario_id', $cuestionario->id)->exists()) {
                    $fallidos[] = $empleado->nombre_completo . " (Error: {$cuestionario->codigo} no en contrato)";
                    continue;
                }

                $existePendiente = EncuestaAsignada::where('empleado_id', $empleado->id)
                    ->where('contrato_id', $contrato->id)
                    ->where('cuestionario_id', $cuestionario->id)
                    ->whereIn('estado', ['pendiente', 'en_progreso'])
                    ->exists();

                if ($existePendiente) {
                    $fallidos[] = $empleado->nombre_completo . " ({$cuestionario->codigo} ya pendiente)";
                    continue;
                }

                try {
                    $totalPreguntas = $cuestionario->preguntas()->count();

                    $asignacion = EncuestaAsignada::create([
                        'empleado_id' => $empleado->id,
                        'contrato_id' => $contrato->id,
                        'cuestionario_id' => $cuestionario->id,
                        'token' => Str::random(64),
                        'estado' => 'pendiente',
                        'total_preguntas' => $totalPreguntas,
                    ]);
                    $asignadosCount++;

                    if ($empleado->email) {
                        try {
                            Mail::to($empleado->email)->send(new EnviarLinkEncuestaMailable($asignacion));
                            Log::info("Email de asignación ({$cuestionario->codigo}) enviado a: {$empleado->email}");
                        } catch (\Exception $e) { $fallidos[] = $empleado->nombre_completo . " (Email Fallido)"; }
                    } else { $fallidos[] = $empleado->nombre_completo . " (Sin Email)"; }
                } catch (QueryException $e) { $fallidos[] = $empleado->nombre_completo . " (Error BD)"; }
                catch (\Exception $e) { $fallidos[] = $empleado->nombre_completo . " (Error General)"; }
            }
        }

        $statusMessage = "Se procesaron {$asignadosCount} nuevas asignaciones.";
        if (!empty($fallidos)) {
            $warningMessage = count($fallidos) . " asignaciones no pudieron ser creadas o notificadas (ya existentes, sin email, o error).";
            return redirect()->route('nom035.asignaciones.create', ['contrato_id' => $contrato->id, 'empresa_id' => $contrato->empresa_id])
                ->with('status', $statusMessage)
                ->with('warning', $warningMessage);
        } else {
            return redirect()->route('nom035.asignaciones.create', ['contrato_id' => $contrato->id, 'empresa_id' => $contrato->empresa_id])
                ->with('status', $statusMessage);
        }
    }
}

