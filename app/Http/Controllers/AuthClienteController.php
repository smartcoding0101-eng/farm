<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthClienteController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión o registro.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('cliente.perfil');
        }
        return view('tienda.auth.login');
    }

    /**
     * Procesa el inicio de sesión.
     */
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credenciales, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('cliente.perfil'))->with('success', '¡Bienvenido de nuevo!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Procesa el registro de un nuevo cliente.
     */
    public function register(Request $request)
    {
        $validados = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users,email',
            'telefono' => 'required|string|max:30',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $usuario = User::create([
            'name' => $validados['name'],
            'email' => $validados['email'],
            'telefono' => $validados['telefono'],
            'role' => 'cliente',
            'password' => Hash::make($validados['password']),
            'activo' => true,
        ]);

        Auth::login($usuario);

        return redirect()->route('cliente.perfil')->with('success', '¡Cuenta creada exitosamente! Bienvenido a NovaStore.');
    }

    /**
     * Cierra la sesión activa.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tienda.inicio')->with('success', 'Sesión cerrada correctamente.');
    }

    /**
     * Perfil del cliente con su historial de pedidos.
     */
    public function perfil()
    {
        $usuario = Auth::user();
        $pedidos = Pedido::with('detalles')
            ->where('user_id', $usuario->id)
            ->orWhere('cliente_email', $usuario->email)
            ->latest()
            ->paginate(10);

        return view('tienda.cliente.perfil', compact('usuario', 'pedidos'));
    }

    /**
     * Búsqueda pública de pedidos por número único (Seguimiento / Rastrear pedido).
     */
    public function rastreo(Request $request)
    {
        $pedido = null;
        if ($request->filled('numero_pedido')) {
            $pedido = Pedido::with(['detalles', 'pagos', 'zonaEnvio'])
                ->where('numero_pedido', trim($request->numero_pedido))
                ->first();
        }

        return view('tienda.rastreo', compact('pedido'));
    }
}
