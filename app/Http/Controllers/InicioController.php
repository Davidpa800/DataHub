<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // <-- 1. IMPORTAR EL FACADE DE LOG

class InicioController extends Controller
{
    /**
     * Muestra la página de inicio pública.
     */
    public function index()
    {
        return view('inicio');
    }

    /**
     * Muestra la página de login.
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Maneja el envío del formulario de contacto.
     */
    public function enviarCorreo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idNombre' => 'required|string|max:255',
            'idCorreo' => 'required|email|max:255',
            'idTelefono' => 'nullable|string|max:20',
            'idMensaje' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $datos = $request->only('idNombre', 'idCorreo', 'idTelefono', 'idRz', 'idMensaje');

        try {
            // Asumiendo que tienes un Mailable llamado 'ContactoMail'
            // Mail::to('contacto@oshconsulting.com.mx')->send(new \App\Mail\ContactoMail($datos));

            // O un correo simple para probar:
            Mail::raw($datos['idMensaje'], function ($message) use ($datos) {
                $message->from($datos['idCorreo'], $datos['idNombre']);
                $message->to('contacto@oshconsulting.com.mx')
                    ->subject('Contacto desde la web - ' . $datos['idRz']);
            });

            return response()->json(['message' => 'Correo enviado exitosamente'], 200);

        } catch (\Exception $e) {
            // Registrar el error real
            \Log::error('Error al enviar correo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al enviar el correo.'], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE AUTENTICACIÓN
    |--------------------------------------------------------------------------
    */

    /**
     * Maneja el intento de inicio de sesión.
     */
    public function handleLogin(Request $request)
    {
        // 1. Validar los datos del formulario
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intentar autenticar al usuario
        if (Auth::attempt($credenciales, $request->filled('remember-me'))) {
            // 3. Si tiene éxito, regenerar la sesión
            $request->session()->regenerate();

            // 4. Redirigir al dashboard
            return redirect()->intended(route('dashboard'));
        }

        // 5. Si falla, volver al login con un mensaje de error

        // <-- 2. AÑADIR ESTA LÍNEA PARA REGISTRAR EL ERROR
        Log::warning('Intento de inicio de sesión fallido', [
            'email' => $request->email,
            'ip_address' => $request->ip()
        ]);

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Muestra el dashboard (página protegida).
     */
    public function showDashboard()
    {
        // Puedes pasar datos del usuario a la vista si lo necesitas
        // $usuario = Auth::user();
        return view('dashboard'); // Apunta a 'resources/views/dashboard.blade.php'
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('inicio'));
    }
}


