<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Configuracion;
use App\Services\CarritoService;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    /**
     * Página de Inicio: Hero moderno, categorías destacadas, ofertas y productos top.
     */
    public function inicio()
    {
        $categorias = Categoria::where('activo', true)
            ->whereNull('categoria_padre_id')
            ->orderBy('orden')
            ->get();

        $destacados = Producto::with(['categoria', 'marca'])
            ->where('estado', 'publicado')
            ->where('destacado', true)
            ->take(8)
            ->get();

        $novedades = Producto::with(['categoria', 'marca'])
            ->where('estado', 'publicado')
            ->latest()
            ->take(8)
            ->get();

        $configs = Configuracion::pluck('valor', 'clave')->toArray();

        return view('tienda.inicio', compact('categorias', 'destacados', 'novedades', 'configs'));
    }

    /**
     * Catálogo general de productos con filtros y ordenamiento.
     */
    public function catalogo(Request $request)
    {
        $query = Producto::with(['categoria', 'marca'])->where('estado', 'publicado');

        // Búsqueda por texto (nombre, SKU o descripción)
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('descripcion_corta', 'like', "%{$q}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->whereHas('categoria', function ($c) use ($request) {
                $c->where('slug', $request->input('categoria'));
            });
        }

        // Filtro por marca
        if ($request->filled('marca')) {
            $query->whereHas('marca', function ($m) use ($request) {
                $m->where('slug', $request->input('marca'));
            });
        }

        // Filtro por rango de precio
        if ($request->filled('min_precio')) {
            $query->where('precio_venta', '>=', (float) $request->input('min_precio'));
        }
        if ($request->filled('max_precio')) {
            $query->where('precio_venta', '<=', (float) $request->input('max_precio'));
        }

        // Ordenamiento
        $orden = $request->input('orden', 'recientes');
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('precio_venta', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio_venta', 'desc');
                break;
            case 'nombre_asc':
                $query->orderBy('nombre', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $productos = $query->paginate(12)->withQueryString();
        $categorias = Categoria::where('activo', true)->get();
        $marcas = Marca::where('activo', true)->get();

        return view('tienda.catalogo', compact('productos', 'categorias', 'marcas'));
    }

    /**
     * Vista de detalle de un producto individual.
     */
    public function producto($slug)
    {
        $producto = Producto::with(['categoria', 'marca', 'imagenes', 'variantes'])
            ->where('slug', $slug)
            ->where('estado', 'publicado')
            ->firstOrFail();

        $relacionados = Producto::where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('estado', 'publicado')
            ->take(4)
            ->get();

        return view('tienda.detalle', compact('producto', 'relacionados'));
    }
}
