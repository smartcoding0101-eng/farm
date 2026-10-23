<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with('padre')->withCount('productos')->orderBy('orden')->paginate(15);
        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        $categoriasPadre = Categoria::whereNull('categoria_padre_id')->where('activo', true)->get();
        return view('admin.categorias.create', compact('categoriasPadre'));
    }

    public function store(Request $request)
    {
        $validados = $request->validate([
            'nombre' => 'required|string|max:150',
            'categoria_padre_id' => 'nullable|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string|max:50',
            'orden' => 'nullable|integer',
            'destacada' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
        ]);

        Categoria::create([
            'nombre' => $validados['nombre'],
            'slug' => Str::slug($validados['nombre']),
            'categoria_padre_id' => $validados['categoria_padre_id'] ?? null,
            'descripcion' => $validados['descripcion'],
            'icono' => $validados['icono'] ?? 'tag',
            'orden' => $validados['orden'] ?? 0,
            'destacada' => $request->boolean('destacada'),
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría registrada con éxito.');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoriasPadre = Categoria::whereNull('categoria_padre_id')->where('id', '!=', $id)->get();
        return view('admin.categorias.edit', compact('categoria', 'categoriasPadre'));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validados = $request->validate([
            'nombre' => 'required|string|max:150',
            'categoria_padre_id' => 'nullable|exists:categorias,id',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string|max:50',
            'orden' => 'nullable|integer',
            'destacada' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
        ]);

        $categoria->update([
            'nombre' => $validados['nombre'],
            'slug' => Str::slug($validados['nombre']),
            'categoria_padre_id' => $validados['categoria_padre_id'] ?? null,
            'descripcion' => $validados['descripcion'],
            'icono' => $validados['icono'] ?? 'tag',
            'orden' => $validados['orden'] ?? 0,
            'destacada' => $request->boolean('destacada'),
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada con éxito.');
    }
}
