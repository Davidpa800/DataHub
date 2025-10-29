<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Parametro;
use App\Models\Contrato;
use App\Models\Cuestionario;
use App\Models\ParametroValor;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class ParametrosController extends Controller
{
    /**
     * Muestra la vista principal de Parámetros del Sistema (con las pestañas).
     */
    public function index()
    {
        $viewName = 'admin.parametros.index';
        if (!view()->exists($viewName)) {
            Log::error("Vista '$viewName' no encontrada.");
            abort(404, "Vista '$viewName' no encontrada.");
        }

        // --- 1. Carga de Parámetros Globales (Colección de clave => valor) ---
        $parameters = Parametro::where('module', '!=', null)
            ->pluck('default_value', 'key');

        // --- 2. Carga de Colecciones de Objetos Parametro para las pestañas dinámicas ---

        $appNom035Params = Parametro::where('module', 'NOM035')
            ->whereIn('param_group', ['app', 'app_nom035'])
            ->get();

        $webNom035Params = Parametro::where('module', 'NOM035')
            ->whereIn('param_group', ['web', 'web_nom035'])
            ->get();

        // --- 3. Preparación de Variables Individuales (para Pestaña 1: Sistema) ---

        $mail_host = $parameters->get('sistema.mail_host', config('mail.mailers.smtp.host'));
        $mail_port = $parameters->get('sistema.mail_port', config('mail.mailers.smtp.port'));
        $mail_username = $parameters->get('sistema.mail_username', config('mail.mailers.smtp.username'));
        $mail_from_address = $parameters->get('sistema.mail_from_address', config('mail.from.address'));
        $mail_from_name = $parameters->get('sistema.mail_from_name', config('mail.from.name'));

        $flujo_consecutivo = false;


        // --- 4. Carga de Contratos para la Pestaña "Contratos" ---
        try {
            $nom035CuestionarioIds = Cuestionario::whereIn('codigo', ['NOM035_G1', 'NOM035_G2', 'NOM035_G3'])->pluck('id')->toArray();

            // Consulta base de contratos: se listan solo si tienen cuestionarios NOM035 asignados
            $contratosListado = Contrato::with('empresa:id,nombre')
                ->where('estado', 'activo')
                ->whereHas('cuestionarios', function ($q) use ($nom035CuestionarioIds) {
                    $q->whereIn('cuestionarios.id', $nom035CuestionarioIds);
                })
                ->with('cuestionarios:id,nombre,codigo')
                ->withCount('empleados')
                ->get();

            Log::debug('Contratos Listado (Muestra): ' . $contratosListado->take(1)->toJson());

            $contractIds = $contratosListado->pluck('id');

            // CARGA CLAVE: Cargar todos los ParametroValor para los contratos
            $allOverrides = ParametroValor::whereIn('contrato_id', $contractIds)
                ->with('parametro:id,key')
                ->get()
                ->groupBy('contrato_id');

            // Mapear los overrides reales a cada contrato
            $contratosListado = $contratosListado->map(function ($contrato) use ($allOverrides) {
                $overridesArray = [];
                $contractOverrides = $allOverrides->get($contrato->id);

                if ($contractOverrides) {
                    foreach ($contractOverrides as $pv) {
                        $key = $pv->parametro->key;
                        $cleanKey = '';

                        // Limpieza de prefijos para que coincida con el input del Blade
                        if (str_starts_with($key, 'app_nom035.')) $cleanKey = substr($key, 11);
                        else if (str_starts_with($key, 'web_nom035.')) $cleanKey = substr($key, 11);
                        else if (str_starts_with($key, 'app.')) $cleanKey = substr($key, 4);
                        else if (str_starts_with($key, 'web.')) $cleanKey = substr($key, 4);
                        else $cleanKey = $key;

                        // Convertir el valor almacenado ('true'/'false') a booleano real (o string)
                        // Este valor es lo que el checkbox usará para saber si está marcado (true) o no (false).
                        $overridesArray[$cleanKey] = filter_var($pv->value, FILTER_VALIDATE_BOOLEAN);
                    }
                }

                // Asignar los overrides reales al contrato
                $contrato->override = $overridesArray;
                return $contrato;
            });

        } catch (\Exception $e) {
            Log::error('Error al cargar listado de contratos para Parámetros: ' . $e->getMessage());
            $contratosListado = collect();
        }

        // --- 5. Definición de Parámetros Anulables (Overrideable Params) ---
        // FILTRO CLAVE: Solo se incluyen los parámetros cuya configuración global está activa ('true').
        $overrideableGroups = ['app', 'app_nom035', 'web', 'web_nom035'];

        $overrideableParams = Parametro::where('module', 'NOM035')
            ->whereIn('param_group', $overrideableGroups)
            ->where('default_value', 'true')
            ->pluck('key')
            ->map(function ($key) {
                // Se limpia el prefijo dinámicamente para obtener el nombre del input (ej: 'mostrar_logo_osh')
                if (str_starts_with($key, 'app_nom035.')) return substr($key, 11);
                if (str_starts_with($key, 'web_nom035.')) return substr($key, 11);
                if (str_starts_with($key, 'app.')) return substr($key, 4);
                if (str_starts_with($key, 'web.')) return substr($key, 4);
                return $key;
            });

        // 6. Pasar todas las variables al Blade
        return view($viewName, compact(
            'parameters',
            'contratosListado',
            'overrideableParams',
            'appNom035Params',
            'webNom035Params',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_from_address',
            'mail_from_name',
            'flujo_consecutivo'
        ));
    }

    /**
     * UTILIDAD TEMPORAL: Establece el valor por defecto de un parámetro a 'false'.
     * Usar para debug/pruebas. Requiere que se pase 'key' en el request.
     * Ejemplo de uso: GET /debug/param/set-false?key=web.mostrar_logo_osh
     */
    public function setParamValueToFalse(Request $request)
    {
        $request->validate(['key' => 'required|string|max:255']);
        $paramKey = $request->input('key');

        try {
            $updated = Parametro::where('key', $paramKey)->update(['default_value' => 'false']);

            if ($updated) {
                Log::info("Parámetro '$paramKey' actualizado a 'false'.", ['user_id' => Auth::id()]);
                return back()->with('status', "Parámetro '$paramKey' cambiado a 'false' con éxito.");
            }

            return back()->with('error', "Parámetro '$paramKey' no encontrado.");
        } catch (\Exception $e) {
            Log::error("Error al establecer '$paramKey' a false: " . $e->getMessage());
            return back()->with('error', 'Error de base de datos al actualizar el parámetro.');
        }
    }

    // -----------------------------------------------------------------------------------
    // --- Métodos de Guardado ---
    // -----------------------------------------------------------------------------------

    public function storeSistema(Request $request)
    {
        $validatedData = $request->validate([
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|numeric',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        $keys = ['mail_host', 'mail_port', 'mail_username', 'mail_from_address', 'mail_from_name'];

        try {
            DB::beginTransaction();
            foreach ($keys as $key) {
                Parametro::updateOrCreate(
                    ['key' => 'sistema.' . $key, 'module' => 'general', 'param_group' => 'sistema'],
                    ['default_value' => $validatedData[$key]]
                );
            }

            if (!empty($validatedData['mail_password'])) {
                Parametro::updateOrCreate(
                    ['key' => 'sistema.mail_password', 'module' => 'general', 'param_group' => 'sistema'],
                    ['default_value' => $validatedData['mail_password']]
                );
            }

            DB::commit();
            Log::info('Guardando Parámetros de Sistema', $request->except('mail_password'));
            return back()->with('status', 'Parámetros del Sistema guardados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar Parámetros de Sistema: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar parámetros de sistema.');
        }
    }

    public function storeApp(Request $request)
    {
        // Obtener todos los parámetros que deberían estar presentes en este formulario
        $relevantParams = Parametro::whereIn('param_group', ['app', 'app_nom035'])
            ->where('module', 'NOM035')
            ->get();

        // 1. Crear reglas de validación dinámicamente
        $validationRules = $relevantParams->pluck('key')->mapWithKeys(function ($key) {
            $inputName = str_replace(['app.', 'app_nom035.'], '', $key);
            return [$inputName => 'nullable|in:on,1'];
        })->toArray();

        $request->validate($validationRules);

        try {
            DB::beginTransaction();

            $totalChanges = 0;

            // 2. Iterar sobre todos los parámetros relevantes y sincronizar el valor
            foreach ($relevantParams as $parametro) {
                $fullKey = $parametro->key;
                $inputName = str_replace(['app.', 'app_nom035.'], '', $fullKey);

                // Si la clave existe en el request, el valor es 'true'
                if ($request->has($inputName)) {
                    $newValue = 'true';
                } else {
                    // Si la clave NO existe en el request, el valor es 'false' (deseleccionado)
                    $newValue = 'false';
                }

                // Si el nuevo valor es diferente al valor actual de la DB, actualizamos y contamos el cambio
                if ($parametro->default_value !== $newValue) {
                    $updated = Parametro::where('key', $fullKey)->update(['default_value' => $newValue]);
                    if ($updated) $totalChanges++;
                }
            }

            DB::commit();

            if ($totalChanges > 0) {
                Log::info('Guardando Parámetros de App', ['request' => $request->all(), 'total_changes' => $totalChanges, 'user_id' => Auth::id()]);
                return back()->with('status', 'Parámetros de la App guardados correctamente.');
            } else {
                return back()->with('status', 'No se realizaron cambios en los parámetros de la App.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar Parámetros de App: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar parámetros de la App.');
        }
    }

    public function storeWebNom035(Request $request)
    {
        // Obtener todos los parámetros que deberían estar presentes en este formulario
        $relevantParams = Parametro::whereIn('param_group', ['web', 'web_nom035'])
            ->where('module', 'NOM035')
            ->get();

        // 1. Crear reglas de validación dinámicamente
        $validationRules = $relevantParams->pluck('key')->mapWithKeys(function ($key) {
            $inputName = str_replace(['web.', 'web_nom035.'], '', $key);
            return [$inputName => 'nullable|in:on,1'];
        })->toArray();

        $request->validate($validationRules);

        try {
            DB::beginTransaction();

            $totalChanges = 0;

            // 2. Iterar sobre todos los parámetros relevantes y sincronizar el valor
            foreach ($relevantParams as $parametro) {
                $fullKey = $parametro->key;
                $inputName = str_replace(['web.', 'web_nom035.'], '', $fullKey);

                // Si la clave existe en el request, el valor es 'true'
                if ($request->has($inputName)) {
                    $newValue = 'true';
                } else {
                    // Si la clave NO existe en el request, el valor es 'false' (deseleccionado)
                    $newValue = 'false';
                }

                // Si el nuevo valor es diferente al valor actual de la DB, actualizamos y contamos el cambio
                if ($parametro->default_value !== $newValue) {
                    $updated = Parametro::where('key', $fullKey)->update(['default_value' => $newValue]);
                    if ($updated) $totalChanges++;
                }
            }

            DB::commit();

            if ($totalChanges > 0) {
                Log::info('Guardando Parámetros Web (NOM-035)', ['request' => $request->all(), 'total_changes' => $totalChanges, 'user_id' => Auth::id()]);
                return back()->with('status', 'Parámetros Web (NOM-035) guardados correctamente.');
            } else {
                return back()->with('status', 'No se realizaron cambios en los parámetros Web (NOM-035).');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar Parámetros Web: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar parámetros web.');
        }
    }

    /**
     * Guarda los parámetros de la pestaña "Empresa" (Simulado)
     */
    public function storeEmpresa(Request $request)
    {
        // Aquí se simula el guardado de parámetros a nivel de empresa (no implementado en BD/form)
        Log::info('Guardando Parámetros de Empresa (Simulado)', $request->all());
        return back()->with('status', 'Parámetros de Empresa guardados (simulación).');
    }

    /**
     * Guarda los parámetros de la pestaña "Contratos" (Implementación real de overrides)
     */
    public function storeContratos(Request $request)
    {
        $request->validate([
            'contrato_override' => 'nullable|array',
        ]);

        $overrides = $request->input('contrato_override', []);

        // Mapear todas las claves de parámetros anulables a sus IDs de la BD para una búsqueda rápida
        $allOverrideKeys = Parametro::where('module', 'NOM035')
            ->whereIn('param_group', ['app', 'app_nom035', 'web', 'web_nom035'])
            ->pluck('id', 'key');

        $allValidParamIds = $allOverrideKeys->values()->toArray();
        $totalChanges = 0; // Inicializar contador de cambios

        try {
            DB::beginTransaction();

            $submittedCompositeKeys = collect([]);

            // 1. Procesar Submisiones (Update/Create los que están marcados como 'true')
            foreach ($overrides as $contratoId => $parametroOverrides) {

                foreach ($parametroOverrides as $keyClean => $value) {

                    $keyPrefixed = collect(['app.', 'web.', 'app_nom035.', 'web_nom035.'])
                        ->map(fn($prefix) => $prefix . $keyClean)
                        ->first(fn($prefixedKey) => $allOverrideKeys->has($prefixedKey));

                    if (!$keyPrefixed) continue;
                    $parametroId = $allOverrideKeys->get($keyPrefixed);

                    $newValue = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

                    // Solo actualizamos/creamos si el valor es 'true'
                    if ($newValue === 'true') {
                        $pv = ParametroValor::updateOrCreate(
                            [
                                'parametro_id' => $parametroId,
                                'contrato_id' => $contratoId,
                                'empresa_id' => null,
                            ],
                            [
                                'value' => 'true',
                                'updated_at' => now(),
                            ]
                        );
                        // Contamos si fue creado o si el valor fue cambiado de false a true
                        if ($pv->wasRecentlyCreated || $pv->wasChanged()) {
                            $totalChanges++;
                        }
                    }

                    // Recolectar la clave compuesta de lo que DEBE ESTAR activo
                    $submittedCompositeKeys->push($contratoId . '-' . $parametroId);
                }
            }

            // 2. Lógica de Sincronización (Actualizar a 'false' los deseleccionados)

            // Si $overrides contiene claves, usamos esas. Si no contiene claves de contrato (porque todo estaba vacío),
            // no podemos determinar qué contratos se estaban editando.
            $contractIds = array_keys($overrides);

            if (empty($contractIds)) {
                // Si el request es vacío, no hay nada que actualizar a false.
                DB::commit();
                return back()->with('status', 'No se realizaron cambios en las anulaciones de contratos.');
            }

            // Encontrar registros existentes que NO fueron enviados (deseleccionados) y cuyo valor actual es 'true'
            $recordsToUpdateToFalse = ParametroValor::whereIn('contrato_id', $contractIds)
                ->whereIn('parametro_id', $allValidParamIds)
                ->where('value', 'true') // Nos enfocamos solo en los que estaban activos
                ->get()
                ->filter(function ($pv) use ($submittedCompositeKeys) {
                    $compositeKey = $pv->contrato_id . '-' . $pv->parametro_id;
                    // Si el registro existe y NO fue enviado como activo, lo marcamos para false.
                    return !$submittedCompositeKeys->contains($compositeKey);
                })
                ->pluck('id')
                ->toArray();

            if (!empty($recordsToUpdateToFalse)) {
                // Actualizar los registros deseleccionados a 'false'
                $updateCount = ParametroValor::whereIn('id', $recordsToUpdateToFalse)
                    ->update(['value' => 'false', 'updated_at' => now()]);

                $totalChanges += $updateCount; // Sumar el número de filas afectadas
            }

            DB::commit();

            // 3. Devolver mensaje basado en el contador de cambios
            if ($totalChanges > 0) {
                Log::info('Guardando Anulaciones de Contratos', ['contratos_modificados' => $contractIds, 'total_changes' => $totalChanges, 'user_id' => Auth::id()]);
                return back()->with('status', 'Anulaciones de contratos guardadas correctamente en la base de datos.');
            } else {
                return back()->with('status', 'No se realizaron cambios en las anulaciones de contratos.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar Anulaciones de Contratos: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar anulaciones de contratos. ' . $e->getMessage());
        }
    }

    /**
     * Guarda un NUEVO parámetro (desde el modal)
     */
    public function storeNuevoParametro(Request $request)
    {
        $validated = $request->validate([
            'param_key' => 'required|string|max:100',
            'param_value' => 'nullable|string|max:255',
            'param_group' => 'required|string|in:sistema,app,app_nom035,web,web_nom035,empresa,contratos',
            'module' => 'required|string|in:NOM035,GENERAL',
            'param_description' => 'nullable|string|max:500',
        ]);

        // Agregar la validación de unicidad de 'key' y 'module'
        $request->validate([
            'param_key' => [
                // CLAVE DE CORRECCIÓN: Especificar la columna real 'key' de la tabla 'parametros'
                Rule::unique('parametros', 'key')->where(fn ($query) => $query->where('module', $validated['module'])),
            ],
        ]);

        try {
            Parametro::create([
                'key' => $validated['param_key'],
                'default_value' => $validated['param_value'],
                'param_group' => $validated['param_group'],
                'module' => $validated['module'],
                'description' => $validated['param_description'],
            ]);

            Log::info('Guardando NUEVO Parámetro', $validated);
            return back()->with('status', 'Nuevo parámetro creado correctamente.');

        } catch (QueryException $e) {
            Log::error('Error al crear nuevo parámetro (QueryException): ' . $e->getMessage());
            return back()->with('error', 'No se pudo crear el parámetro por un error de BD.');
        } catch (\Exception $e) {
            Log::error('Error general al crear nuevo parámetro: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error inesperado al guardar.');
        }
    }
}
