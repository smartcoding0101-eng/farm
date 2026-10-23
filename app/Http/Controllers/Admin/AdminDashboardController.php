<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use App\Models\Pago;
use App\Models\Categoria;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Panel general con métricas en vivo.
     */
    public function index()
    {
        $ventasTotales = Pedido::where('estado_pago', 'pagado')->sum('total');
        $pedidosPendientes = Pedido::where('estado_pedido', 'pendiente')->count();
        $pagosPorVerificar = Pago::where('estado', 'en_revision')->count();
        $stockCritico = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count();

        $pedidosRecientes = Pedido::with('pagos')->latest()->take(5)->get();
        $ultimosMovimientos = MovimientoInventario::with('producto')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'ventasTotales',
            'pedidosPendientes',
            'pagosPorVerificar',
            'stockCritico',
            'pedidosRecientes',
            'ultimosMovimientos'
        ));
    }

    /**
     * Reporte detallado de movimientos de inventario (Kardex).
     */
    public function reporteMovimientos(Request $request)
    {
        $query = MovimientoInventario::with(['producto', 'usuario', 'pedido']);

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('producto_id')) {
            $query->where('producto_id', $request->producto_id);
        }

        $movimientos = $query->latest()->paginate(15)->withQueryString();
        $productos = Producto::orderBy('nombre')->get();

        return view('admin.reportes.movimientos', compact('movimientos', 'productos'));
    }

    /**
     * Reporte de ventas y rentabilidad.
     */
    public function reporteVentas(Request $request)
    {
        $query = Pedido::with('detalles');

        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }

        $pedidos = $query->latest()->paginate(15)->withQueryString();

        $ingresoBruto = Pedido::where('estado_pago', 'pagado')->sum('subtotal');
        $descuentos = Pedido::where('estado_pago', 'pagado')->sum('descuento');
        $costosEnvio = Pedido::where('estado_pago', 'pagado')->sum('costo_envio');
        $totalCobrado = Pedido::where('estado_pago', 'pagado')->sum('total');

        return view('admin.reportes.ventas', compact(
            'pedidos',
            'ingresoBruto',
            'descuentos',
            'costosEnvio',
            'totalCobrado'
        ));
    }

    /**
     * Listado de pagos recibidos para verificación (QR / Transferencia).
     */
    public function pagos()
    {
        $pagos = Pago::with(['pedido', 'verificador'])->latest()->paginate(15);
        return view('admin.pagos.index', compact('pagos'));
    }

    /**
     * Valida o rechaza un pago con comprobante.
     */
    public function cambiarEstadoPago(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:aprobado,rechazado',
            'motivo_rechazo' => 'nullable|string|max:250',
        ]);

        $pago = Pago::findOrFail($id);
        $pago->update([
            'estado' => $request->estado,
            'verificado_por' => auth()->id() ?? 1,
            'fecha_verificacion' => now(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if ($request->estado === 'aprobado') {
            $pago->pedido->update([
                'estado_pago' => 'pagado',
                'estado_pedido' => 'confirmado',
            ]);
            $mensaje = 'Pago verificado y aprobado. El pedido ha sido confirmado.';
        } else {
            $pago->pedido->update([
                'estado_pago' => 'fallido',
            ]);
            $mensaje = 'Pago rechazado.';
        }

        return back()->with('success', $mensaje);
    }

    /**
     * Pantalla de configuraciones de la tienda.
     */
    public function configuracion()
    {
        $configs = Configuracion::pluck('valor', 'clave')->toArray();
        return view('admin.configuracion.index', compact('configs'));
    }

    public function guardarConfiguracion(Request $request)
    {
        $campos = [
            'nombre_tienda', 'slogan', 'telefono_whatsapp', 'email_contacto',
            'direccion_tienda', 'horario_atencion', 'qr_banco', 'qr_titular',
            'mensaje_whatsapp_default', 'moneda', 'simbolo_moneda'
        ];

        foreach ($campos as $campo) {
            if ($request->has($campo)) {
                Configuracion::set($campo, $request->input($campo));
            }
        }

        return back()->with('success', 'Configuración de la tienda guardada con éxito.');
    }
}
