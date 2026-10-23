<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\MovimientoInventario;
use App\Services\ProductoImportExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'marca']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $productos = $query->latest()->paginate(15)->withQueryString();
        $categorias = Categoria::where('activo', true)->get();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::where('activo', true)->get();
        $marcas = Marca::where('activo', true)->get();

        return view('admin.productos.create', compact('categorias', 'marcas'));
    }

    public function store(Request $request)
    {
        $validados = $request->validate([
            'nombre' => 'required|string|max:200',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'sku' => 'required|string|max:60|unique:productos,sku',
            'precio_venta' => 'required|numeric|min:0',
            'costo_adquisicion' => 'required|numeric|min:0',
            'precio_oferta' => 'nullable|numeric|min:0',
            'stock_inicial' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:1',
            'descripcion_corta' => 'nullable|string|max:500',
            'descripcion_completa' => 'nullable|string',
            'destacado' => 'nullable|boolean',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create([
            'nombre' => $validados['nombre'],
            'slug' => Str::slug($validados['nombre']) . '-' . Str::random(4),
            'categoria_id' => $validados['categoria_id'],
            'marca_id' => $validados['marca_id'],
            'sku' => strtoupper($validados['sku']),
            'precio_venta' => $validados['precio_venta'],
            'costo_adquisicion' => $validados['costo_adquisicion'],
            'precio_oferta' => $validados['precio_oferta'],
            'stock_actual' => $validados['stock_inicial'],
            'stock_minimo' => $validados['stock_minimo'],
            'descripcion_corta' => $validados['descripcion_corta'],
            'descripcion_completa' => $validados['descripcion_completa'],
            'destacado' => $request->boolean('destacado'),
            'imagen_principal' => $rutaImagen ? '/storage/' . $rutaImagen : null,
            'estado' => 'publicado',
        ]);

        // Registrar en Kardex si tiene stock inicial
        if ($validados['stock_inicial'] > 0) {
            MovimientoInventario::create([
                'producto_id' => $producto->id,
                'user_id' => auth()->id() ?? 1,
                'tipo' => 'entrada',
                'cantidad' => $validados['stock_inicial'],
                'stock_anterior' => 0,
                'stock_posterior' => $validados['stock_inicial'],
                'motivo' => 'Alta inicial de producto en catálogo',
            ]);
        }

        return redirect()->route('admin.productos.index')->with('success', 'Producto registrado exitosamente.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('activo', true)->get();
        $marcas = Marca::where('activo', true)->get();

        return view('admin.productos.edit', compact('producto', 'categorias', 'marcas'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validados = $request->validate([
            'nombre' => 'required|string|max:200',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'sku' => 'required|string|max:60|unique:productos,sku,' . $producto->id,
            'precio_venta' => 'required|numeric|min:0',
            'costo_adquisicion' => 'required|numeric|min:0',
            'precio_oferta' => 'nullable|numeric|min:0',
            'stock_minimo' => 'required|integer|min:1',
            'descripcion_corta' => 'nullable|string|max:500',
            'descripcion_completa' => 'nullable|string',
            'destacado' => 'nullable|boolean',
            'estado' => 'required|in:publicado,borrador,archivado',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
            $validados['imagen_principal'] = '/storage/' . $rutaImagen;
        }

        $validados['destacado'] = $request->boolean('destacado');
        $producto->update($validados);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function ajustarStock(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:250',
        ]);

        $producto = Producto::findOrFail($id);
        $stockAnterior = $producto->stock_actual;

        if ($request->tipo === 'entrada') {
            $stockPosterior = $stockAnterior + $request->cantidad;
        } elseif ($request->tipo === 'salida') {
            if ($request->cantidad > $stockAnterior) {
                return back()->with('error', 'La cantidad a retirar supera el stock actual.');
            }
            $stockPosterior = $stockAnterior - $request->cantidad;
        } else {
            $stockPosterior = $request->cantidad;
        }

        $producto->update(['stock_actual' => $stockPosterior]);

        MovimientoInventario::create([
            'producto_id' => $producto->id,
            'user_id' => auth()->id() ?? 1,
            'tipo' => $request->tipo,
            'cantidad' => $request->cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_posterior' => $stockPosterior,
            'motivo' => $request->motivo,
        ]);

        return back()->with('success', "Stock actualizado en Kardex (Nuevo stock: {$stockPosterior}).");
    }

    /**
     * Descarga la plantilla oficial en formato Excel .xlsx con ejemplos y guía
     */
    public function descargarPlantilla(ProductoImportExportService $importService)
    {
        $spreadsheet = $importService->generarPlantillaExcel();
        $fileName = 'Plantilla_Carga_Masiva_Farmaluz_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Procesa la carga masiva desde el archivo Excel y ZIP opcional
     */
    public function importarExcel(Request $request, ProductoImportExportService $importService)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'archivo_zip' => 'nullable|file|mimes:zip|max:51200',
        ], [
            'archivo_excel.required' => 'Debes seleccionar el archivo de Excel con los productos.',
            'archivo_excel.mimes' => 'El archivo debe tener formato .xlsx, .xls o .csv.',
            'archivo_zip.mimes' => 'El archivo de imágenes adjunto debe ser un archivo comprimido .zip.',
        ]);

        $userId = auth()->id() ?? 1;
        $resultado = $importService->importarProductos(
            $request->file('archivo_excel'),
            $request->file('archivo_zip'),
            $userId
        );

        if (!$resultado['success']) {
            return back()->with('error', $resultado['mensaje'] ?? 'Ocurrió un error al procesar el archivo Excel.');
        }

        $msg = "Carga masiva completada: {$resultado['creados']} productos creados y {$resultado['actualizados']} actualizados con éxito.";
        if (!empty($resultado['errores'])) {
            $msg .= ' Avisos: ' . implode(' | ', array_slice($resultado['errores'], 0, 3));
        }

        return redirect()->route('admin.productos.index')->with('success', $msg);
    }
}
