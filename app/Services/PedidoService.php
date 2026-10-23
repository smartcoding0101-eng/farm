<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use App\Models\Cupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PedidoService
{
    /**
     * Procesa la creación de un nuevo pedido de manera atómica con transacción de base de datos.
     */
    public static function crearPedido(array $datosCliente, array $datosEntrega, string $metodoPago, ?string $notas = null): Pedido
    {
        return DB::transaction(function () use ($datosCliente, $datosEntrega, $metodoPago, $notas) {
            $carrito = CarritoService::obtenerCarrito();
            if (empty($carrito)) {
                throw new \Exception("El carrito de compras está vacío.");
            }

            // Recalcular montos en servidor
            $zonaId = $datosEntrega['modalidad'] === 'domicilio' ? ($datosEntrega['zona_envio_id'] ?? null) : null;
            $totales = CarritoService::calcularTotales($zonaId, $datosEntrega['modalidad']);

            // Generar número de pedido único y formal: PED-YYYYMMDD-XXXX
            $numeroPedido = 'PED-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $pedido = Pedido::create([
                'numero_pedido' => $numeroPedido,
                'user_id' => auth()->id(),
                'cliente_nombre' => $datosCliente['nombre'],
                'cliente_email' => $datosCliente['email'] ?? null,
                'cliente_telefono' => $datosCliente['telefono'],
                'modalidad_entrega' => $datosEntrega['modalidad'],
                'zona_envio_id' => $zonaId,
                'departamento' => $datosEntrega['departamento'] ?? 'La Paz',
                'ciudad' => $datosEntrega['ciudad'] ?? 'La Paz',
                'direccion_envio' => $datosEntrega['direccion'] ?? 'Retiro en Sucursal',
                'referencia_envio' => $datosEntrega['referencia'] ?? null,
                'subtotal' => $totales['subtotal'],
                'descuento' => $totales['descuento'],
                'costo_envio' => $totales['costo_envio'],
                'total' => $totales['total'],
                'cupon_aplicado' => $totales['cupon_codigo'],
                'estado_pedido' => 'pendiente',
                'metodo_pago' => $metodoPago,
                'estado_pago' => ($metodoPago === 'efectivo_contraentrega') ? 'pendiente' : 'en_verificacion',
                'notas_cliente' => $notas,
            ]);

            // Guardar detalles y descontar existencias con bloqueo pesimista
            foreach ($carrito as $item) {
                $producto = Producto::lockForUpdate()->find($item['producto_id']);
                if (!$producto || $producto->stock_actual < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$item['nombre']}");
                }

                $costoAdquisicion = $producto->costo_adquisicion;
                $subtotalLinea = $item['precio'] * $item['cantidad'];

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'variante_id' => $item['variante_id'] ?? null,
                    'producto_nombre' => $producto->nombre,
                    'sku' => $producto->sku,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'costo_adquisicion_unitario' => $costoAdquisicion,
                    'subtotal' => $subtotalLinea,
                ]);

                // Actualizar stock y registrar en Kardex
                $stockAnterior = $producto->stock_actual;
                $producto->decrement('stock_actual', $item['cantidad']);

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'variante_id' => $item['variante_id'] ?? null,
                    'user_id' => auth()->id(),
                    'pedido_id' => $pedido->id,
                    'tipo' => 'salida',
                    'cantidad' => $item['cantidad'],
                    'stock_anterior' => $stockAnterior,
                    'stock_posterior' => $stockAnterior - $item['cantidad'],
                    'motivo' => "Venta por pedido {$numeroPedido}",
                ]);
            }

            // Registrar registro de transacción de Pago inicial
            $metodoTipo = ($metodoPago === 'qr_simple') ? 'qr' : (($metodoPago === 'transferencia') ? 'transferencia' : 'efectivo');
            Pago::create([
                'pedido_id' => $pedido->id,
                'referencia_pago' => 'PAG-' . strtoupper(Str::random(10)),
                'metodo' => $metodoTipo,
                'monto' => $totales['total'],
                'estado' => 'pendiente',
            ]);

            // Incrementar uso del cupón si se aplicó
            if ($totales['cupon_codigo']) {
                Cupon::where('codigo', $totales['cupon_codigo'])->increment('usos_actuales');
            }

            // Limpiar carrito tras completar la compra
            CarritoService::vaciar();

            return $pedido;
        });
    }
}
