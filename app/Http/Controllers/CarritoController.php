<?php

namespace App\Http\Controllers;

use App\Services\CarritoService;
use App\Models\Cupon;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = CarritoService::obtenerCarrito();
        $totales = CarritoService::calcularTotales();

        return view('tienda.carrito', compact('carrito', 'totales'));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad' => 'nullable|integer|min:1',
            'variante_id' => 'nullable|integer|exists:variantes_producto,id',
        ]);

        $cantidad = $request->input('cantidad', 1);
        $resultado = CarritoService::agregar($request->producto_id, $cantidad, $request->variante_id);

        if (!$resultado['exito']) {
            return back()->with('error', $resultado['mensaje']);
        }

        return redirect()->route('carrito.index')->with('success', $resultado['mensaje']);
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'clave' => 'required|string',
            'cantidad' => 'required|integer|min:0',
        ]);

        $resultado = CarritoService::actualizarCantidad($request->clave, $request->cantidad);

        return back()->with($resultado['exito'] ? 'success' : 'error', $resultado['mensaje']);
    }

    public function eliminar($clave)
    {
        CarritoService::eliminar($clave);
        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function aplicarCupon(Request $request)
    {
        $request->validate(['codigo' => 'required|string']);
        $codigo = trim($request->codigo);

        $totales = CarritoService::calcularTotales();
        $cupon = Cupon::where('codigo', $codigo)->first();

        if (!$cupon || !$cupon->esValido($totales['subtotal'])) {
            return back()->with('error', 'El cupón no es válido o no cumple con el monto mínimo requerido.');
        }

        session()->put('cupon_aplicado', $cupon->codigo);
        return back()->with('success', "¡Cupón {$cupon->codigo} aplicado correctamente!");
    }

    public function removerCupon()
    {
        session()->forget('cupon_aplicado');
        return back()->with('success', 'Cupón removido.');
    }
}
