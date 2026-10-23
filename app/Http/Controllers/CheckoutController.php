<?php

namespace App\Http\Controllers;

use App\Services\CarritoService;
use App\Services\PedidoService;
use App\Models\ZonaEnvio;
use App\Models\Pedido;
use App\Models\Pago;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Muestra la pantalla de Checkout / Finalizar compra.
     */
    public function index()
    {
        $carrito = CarritoService::obtenerCarrito();
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío. Agrega productos para comprar.');
        }

        $zonas = ZonaEnvio::where('activo', true)->get();
        $primerZonaId = $zonas->first()?->id;
        $totales = CarritoService::calcularTotales($primerZonaId);
        $configs = Configuracion::pluck('valor', 'clave')->toArray();

        return view('tienda.checkout', compact('carrito', 'zonas', 'totales', 'configs'));
    }

    /**
     * Procesa la orden de compra.
     */
    public function procesar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'modalidad' => 'required|in:domicilio,retiro_tienda',
            'zona_envio_id' => 'required_if:modalidad,domicilio|nullable|exists:zonas_envio,id',
            'direccion' => 'required_if:modalidad,domicilio|nullable|string|max:300',
            'referencia' => 'nullable|string|max:200',
            'metodo_pago' => 'required|in:qr_simple,transferencia,tarjeta,efectivo_contraentrega',
            'notas' => 'nullable|string|max:500',
        ]);

        try {
            $datosCliente = [
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'email' => $request->email,
            ];

            $datosEntrega = [
                'modalidad' => $request->modalidad,
                'zona_envio_id' => $request->zona_envio_id,
                'direccion' => $request->direccion,
                'referencia' => $request->referencia,
            ];

            $pedido = PedidoService::crearPedido(
                $datosCliente,
                $datosEntrega,
                $request->metodo_pago,
                $request->notas
            );

            return redirect()->route('checkout.confirmacion', ['numero' => $pedido->numero_pedido]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Muestra la pantalla de confirmación, QR de pago y subida de comprobante.
     */
    public function confirmacion($numero)
    {
        $pedido = Pedido::with(['detalles.producto', 'zonaEnvio', 'pagos'])
            ->where('numero_pedido', $numero)
            ->firstOrFail();

        $configs = Configuracion::pluck('valor', 'clave')->toArray();

        return view('tienda.confirmacion', compact('pedido', 'configs'));
    }

    /**
     * Permite al cliente subir su comprobante de pago por QR o transferencia.
     */
    public function subirComprobante(Request $request, $numero)
    {
        $request->validate([
            'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Máx 5MB
        ]);

        $pedido = Pedido::where('numero_pedido', $numero)->firstOrFail();
        $pago = $pedido->pagos()->first();

        if ($request->hasFile('comprobante')) {
            $archivo = $request->file('comprobante');
            $nombreArchivo = 'comp_' . $pedido->numero_pedido . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = $archivo->storeAs('comprobantes', $nombreArchivo, 'public');

            if ($pago) {
                $pago->update([
                    'comprobante_archivo' => $ruta,
                    'estado' => 'en_revision',
                ]);
            }

            $pedido->update(['estado_pago' => 'en_verificacion']);

            return back()->with('success', '¡Comprobante subido exitosamente! Nuestro equipo lo validará a la brevedad.');
        }

        return back()->with('error', 'No se pudo procesar el archivo del comprobante.');
    }
}
