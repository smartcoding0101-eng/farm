<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZonaEnvio;
use Illuminate\Http\Request;

class AdminZonaEnvioController extends Controller
{
    public function index()
    {
        $zonas = ZonaEnvio::latest()->paginate(15);
        return view('admin.zonas.index', compact('zonas'));
    }

    public function create()
    {
        return view('admin.zonas.create');
    }

    public function store(Request $request)
    {
        $validados = $request->validate([
            'nombre' => 'required|string|max:100',
            'departamento' => 'required|string|max:50',
            'costo_envio' => 'required|numeric|min:0',
            'monto_minimo_envio_gratis' => 'nullable|numeric|min:0',
            'tiempo_estimado' => 'required|string|max:80',
            'activo' => 'nullable|boolean',
        ]);

        ZonaEnvio::create([
            'nombre' => $validados['nombre'],
            'departamento' => $validados['departamento'],
            'costo_envio' => $validados['costo_envio'],
            'monto_minimo_envio_gratis' => $validados['monto_minimo_envio_gratis'],
            'tiempo_estimado' => $validados['tiempo_estimado'],
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.zonas.index')->with('success', 'Zona de envío registrada exitosamente.');
    }

    public function edit($id)
    {
        $zona = ZonaEnvio::findOrFail($id);
        return view('admin.zonas.edit', compact('zona'));
    }

    public function update(Request $request, $id)
    {
        $zona = ZonaEnvio::findOrFail($id);

        $validados = $request->validate([
            'nombre' => 'required|string|max:100',
            'departamento' => 'required|string|max:50',
            'costo_envio' => 'required|numeric|min:0',
            'monto_minimo_envio_gratis' => 'nullable|numeric|min:0',
            'tiempo_estimado' => 'required|string|max:80',
            'activo' => 'nullable|boolean',
        ]);

        $zona->update([
            'nombre' => $validados['nombre'],
            'departamento' => $validados['departamento'],
            'costo_envio' => $validados['costo_envio'],
            'monto_minimo_envio_gratis' => $validados['monto_minimo_envio_gratis'],
            'tiempo_estimado' => $validados['tiempo_estimado'],
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.zonas.index')->with('success', 'Zona de envío actualizada.');
    }
}
