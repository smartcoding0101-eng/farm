<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tienda;
use App\Services\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminTiendaController extends Controller
{
    /**
     * Permite al usuario cambiar entre sus diferentes tiendas disponibles
     */
    public function switchTienda(Request $request)
    {
        $request->validate([
            'tienda_id' => 'required|exists:tiendas,id',
        ]);

        $user = Auth::user();
        $tiendaId = (int) $request->tienda_id;

        $tenantManager = app(TenantManager::class);

        // Validar acceso estricto a la tienda
        if (!$tenantManager->userHasAccessToTienda($user, $tiendaId)) {
            return back()->with('error', 'No tienes autorización para acceder a la tienda seleccionada.');
        }

        $tienda = Tienda::findOrFail($tiendaId);
        $tenantManager->setTienda($tienda);

        return back()->with('success', "Has cambiado a la tienda: {$tienda->nombre}");
    }

    /**
     * Lista de tiendas del usuario
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('cliente.login')->with('error', 'Por favor inicia sesión para gestionar tus tiendas.');
        }

        $tiendas = $user->tiendas()->withCount(['productos', 'pedidos'])->get();

        return view('admin.tiendas.index', compact('tiendas'));
    }

    /**
     * Formulario de creación de una nueva tienda para el usuario
     */
    public function create()
    {
        return view('admin.tiendas.create');
    }

    /**
     * Guarda una nueva tienda y la asigna al usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'slug' => 'nullable|string|max:180|unique:tiendas,slug',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'moneda' => 'required|string|max:10',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nombre']) . '-' . Str::random(4);
        }

        $tienda = Tienda::create([
            'nombre' => $validated['nombre'],
            'slug' => $validated['slug'],
            'telefono' => $validated['telefono'] ?? null,
            'email' => $validated['email'] ?? null,
            'moneda' => $validated['moneda'] ?? 'BOB',
            'activo' => true,
        ]);

        // Asignar al usuario actual como dueño
        $user = Auth::user();
        $user->tiendas()->attach($tienda->id, [
            'rol' => 'dueno',
            'es_predeterminada' => false,
        ]);

        // Cambiar inmediatamente a la nueva tienda
        app(TenantManager::class)->setTienda($tienda);

        return redirect()->route('admin.dashboard')->with('success', "¡Tienda '{$tienda->nombre}' creada con éxito y seleccionada como activa!");
    }
}
