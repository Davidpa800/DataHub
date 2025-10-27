<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
// use App\Mail\ContactFormMail; // Descomentar cuando se cree
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Empresa; // Necesario para contar en dashboard
use App\Models\Contrato; // Necesario para contar en dashboard
use Illuminate\Support\Facades\Artisan; // Para limpiar caché (si es posible)
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;


class InicioController extends Controller
{
    // --- MÉTODOS PÚBLICOS ---
    /** Muestra la página de inicio pública. */
    public function showInicio() { return view('inicio'); }
    /** Muestra la página de servicios (placeholder). */
    public function showServicios() { if (!view()->exists('servicios')) { abort(404); } return view('servicios'); }
    /** Muestra la página de publicaciones (placeholder). */
    public function showPublicaciones() { if (!view()->exists('publicaciones')) { abort(404); } return view('publicaciones'); }
    /** Maneja el envío del formulario de contacto público. */
    public function handleContactForm(Request $request) {
        $validator = Validator::make($request->all(), [
            'modalNombreS' => 'required|string|max:255',
            'modalTelS' => 'nullable|string|max:20',
            'modalEmailS' => 'required|email|max:255',
            'modalRazonS' => 'nullable|string|max:255',
            'modalMensajeS' => 'required|string|max:2000',
        ], [
            'modalNombreS.required' => 'El nombre completo es obligatorio.',
            'modalEmailS.required' => 'El correo electrónico es obligatorio.',
            'modalEmailS.email' => 'Por favor, introduce un correo electrónico válido.',
            'modalMensajeS.required' => 'El mensaje no puede estar vacío.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $validator->validated();
            $recipient = config('mail.from.address', env('MAIL_FROM_ADDRESS', 'default@example.com'));

            // Mail::to($recipient)->send(new ContactFormMail($data)); // Descomentar al crear Mailable

            Log::info('Correo de contacto recibido:', [
                'nombre' => $data['modalNombreS'], 'telefono' => $data['modalTelS'], 'email' => $data['modalEmailS'],
                'razon_social' => $data['modalRazonS'], 'mensaje' => $data['modalMensajeS'], 'ip' => $request->ip()
            ]);

            return response()->json(['message' => 'Tu mensaje ha sido enviado con éxito.'], 200);

        } catch (\Exception $e) {
            Log::error('Error al enviar correo de contacto: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Hubo un problema al enviar el correo. Por favor, inténtalo más tarde.'], 500);
        }
    }

    // --- MÉTODOS DE AUTENTICACIÓN ---
    /** Muestra la vista de inicio de sesión. */
    public function showLogin() { return view('login'); }
    /** Maneja el intento de inicio de sesión. */
    public function handleLogin(Request $request) {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credenciales, $request->boolean('remember-me'))) {
            $request->session()->regenerate();
            Log::info('Inicio de sesión exitoso', ['email' => $request->email, 'ip_address' => $request->ip()]);
            return redirect()->intended('dashboard');
        }

        Log::warning('Intento de inicio de sesión fallido', ['email' => $request->email, 'ip_address' => $request->ip()]);
        return back()->withErrors(['email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.'])->onlyInput('email');
    }
    /** Maneja el cierre de sesión. */
    public function handleLogout(Request $request) {
        $userEmail = Auth::user() ? Auth::user()->email : 'N/A';
        $ipAddress = $request->ip();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('Cierre de sesión', ['email' => $userEmail, 'ip_address' => $ipAddress]);
        return redirect('/login')->with('status', 'Has cerrado sesión correctamente.');
    }

    // --- MÉTODOS DEL DASHBOARD PRINCIPAL ---
    /** Muestra la vista principal del dashboard con datos generales. */
    public function showDashboardIndex()
    {
        // Esta lógica ahora solo calcula datos generales, no NOM035 específicos
        try {
            $totalUsuarios = User::count();
            $totalRoles = \Spatie\Permission\Models\Role::count();
            $totalEmpresas = Empresa::count(); // Contamos todas las empresas
            $totalContratos = Contrato::count(); // Contamos todos los contratos
            // Puedes añadir más métricas generales si lo deseas

        } catch (\Exception $e) {
            Log::error('Error general al cargar datos del dashboard principal: ' . $e->getMessage());
            $totalUsuarios = $totalRoles = $totalEmpresas = $totalContratos = 'Error';
            session()->flash('error', 'No se pudieron cargar algunos datos del dashboard.');
        }

        // Asegúrate que la vista 'admin.index' exista
        if (!view()->exists('admin.index')) {
            Log::error("Vista 'admin.index' no encontrada.");
            abort(404, "Vista principal del dashboard no encontrada.");
        }

        return view('admin.index', compact(
            'totalUsuarios',
            'totalRoles',
            'totalEmpresas', // Pasamos los totales generales
            'totalContratos'
        // Ya no pasamos datos específicos de NOM035 aquí
        ));
    }


    // --- PERFIL Y CONFIGURACIÓN ---
    /** Muestra la página de perfil unificada (Perfil y Configuración). */
    public function showProfile(Request $request) {
        $user = $request->user();
        // Placeholder: Usando config() por ahora
        $settingsGeneral = ['app_name' => config('app.name', 'Laravel'), 'app_timezone' => config('app.timezone', 'UTC')];
        $settingsEmail = [
            'mail_mailer' => config('mail.default', 'log'), 'mail_host' => config('mail.mailers.smtp.host', ''),
            'mail_port' => config('mail.mailers.smtp.port', ''), 'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_encryption' => config('mail.mailers.smtp.encryption', ''), 'mail_from_address' => config('mail.from.address', ''),
            'mail_from_name' => config('mail.from.name', ''),
        ];
        $proyectos = collect(); // Vacío por ahora

        // Asegúrate que la vista 'admin.profile' exista
        if (!view()->exists('admin.profile')) {
            Log::error("Vista 'admin.profile' no encontrada.");
            abort(404, "Vista de perfil no encontrada.");
        }

        return view('admin.profile', compact('user', 'proyectos', 'settingsGeneral', 'settingsEmail'));
    }
    /** Actualiza la información del perfil del usuario. */
    public function updateProfile(Request $request) {
        $user = $request->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        $user->fill($validated);
        // if ($user->isDirty('email')) { $user->email_verified_at = null; } // Si usas verificación
        $user->save();
        Log::info('Perfil actualizado', ['user_id' => $user->id, 'ip_address' => $request->ip()]);
        return redirect()->route('profile.show')->with('status', 'Información del perfil actualizada correctamente.');
    }
    /** Actualiza la contraseña del usuario. */
    public function updatePassword(Request $request) {
        $user = $request->user();
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user->update(['password' => Hash::make($validated['password']),]);
        Log::info('Contraseña actualizada', ['user_id' => $user->id, 'ip_address' => $request->ip()]);
        return redirect()->route('profile.show')->with('status', 'Contraseña actualizada correctamente.');
    }
    /** Actualiza las configuraciones generales (placeholder). */
    public function updateGeneralSettings(Request $request) {
        $validated = $request->validate(['app_name' => 'required|string|max:255', 'app_timezone' => 'required|string|max:255|timezone',]);
        Log::info('Configuraciones generales actualizadas (simulado)', ['settings' => $validated, 'user_id' => $request->user()->id, 'ip_address' => $request->ip()]);
        // Lógica para guardar...
        try { if (file_exists(base_path('bootstrap/cache/config.php'))) { unlink(base_path('bootstrap/cache/config.php')); } }
        catch (\Exception $e) { Log::warning("No se pudo limpiar caché config manual: " . $e->getMessage()); }
        return redirect()->route('profile.show')->with('status', 'Configuración general actualizada.');
    }
    /** Actualiza las configuraciones de correo (placeholder). */
    public function updateEmailSettings(Request $request) {
        $validated = $request->validate([
            'mail_mailer' => 'required|string|in:smtp,log,array,sendmail', 'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535', 'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255', 'mail_encryption' => 'nullable|string|in:tls,ssl,starttls',
            'mail_from_address' => 'required|email|max:255', 'mail_from_name' => 'required|string|max:255',
        ]);
        Log::info('Configuraciones de correo actualizadas (simulado)', [/*'settings' => $validated,*/ 'user_id' => $request->user()->id, 'ip_address' => $request->ip()]); // No loguear pass
        // Lógica para guardar...
        try { if (file_exists(base_path('bootstrap/cache/config.php'))) { unlink(base_path('bootstrap/cache/config.php')); } }
        catch (\Exception $e) { Log::warning("No se pudo limpiar caché config manual: " . $e->getMessage()); }
        return redirect()->route('profile.show')->with('status', 'Configuración de correo actualizada.');
    }

    // --- ADMINISTRACIÓN (Permisos, Usuarios) ---
    /** Muestra la vista para gestionar permisos. */
    public function showPermissions() {
        if (!view()->exists('admin.permissions.index')) { abort(404, "Vista 'admin.permissions.index' no encontrada."); }
        $permissions = Permission::orderBy('name')->get();
        return view('admin.permissions.index', compact('permissions'));
    }
    /** Guarda un nuevo permiso. */
    public function storePermission(Request $request) {
        $validated = $request->validate(['name' => 'required|string|max:255|unique:permissions,name',]);
        try {
            Permission::create(['name' => $validated['name'], 'guard_name' => 'web']);
            Log::info('Nuevo permiso creado', ['name' => $validated['name'], 'user_id' => $request->user()->id, 'ip_address' => $request->ip()]);
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            return redirect()->route('admin.permissions.index')->with('status', 'Permiso "'.$validated['name'].'" creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear permiso: ' . $e->getMessage());
            return back()->with('error', 'No se pudo crear el permiso. Inténtalo de nuevo.');
        }
    }
    /** Muestra la vista para gestionar usuarios. */
    public function showUsers() {
        if (!view()->exists('admin.users.index')) { abort(404, "Vista 'admin.users.index' no encontrada."); }
        try {
            $users = User::with('roles')->orderBy('name')->paginate(15);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            $users = new LengthAwarePaginator([], 0, 15); // Paginador vacío
            session()->flash('error', 'No se pudieron cargar los usuarios.');
        }
        return view('admin.users.index', compact('users'));
    }

}

