<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Cupon;
use App\Models\ZonaEnvio;

class CarritoService
{
    /**
     * Obtiene los ítems del carrito desde la sesión.
     */
    public static function obtenerCarrito(): array
    {
        return session()->get('carrito', []);
    }

    /**
     * Añade un producto al carrito verificando stock disponible.
     */
    public static function agregar(int $productoId, int $cantidad = 1, ?int $varianteId = null): array
    {
        $producto = Producto::find($productoId);
        if (!$producto || $producto->estado !== 'publicado') {
            return ['exito' => false, 'mensaje' => 'El producto no está disponible.'];
        }

        $stockDisponible = $producto->stock_actual;
        $carrito = session()->get('carrito', []);
        $clave = $varianteId ? "{$productoId}_{$varianteId}" : "{$productoId}";

        $cantidadActual = isset($carrito[$clave]) ? $carrito[$clave]['cantidad'] : 0;
        $nuevaCantidad = $cantidadActual + $cantidad;

        if ($nuevaCantidad > $stockDisponible) {
            return [
                'exito' => false,
                'mensaje' => "Stock insuficiente. Solo quedan {$stockDisponible} unidades disponibles."
            ];
        }

        $precio = $producto->precio_efectivo;

        $carrito[$clave] = [
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
            'slug' => $producto->slug,
            'sku' => $producto->sku,
            'precio' => $precio,
            'cantidad' => $nuevaCantidad,
            'imagen' => $producto->imagen_principal ?? '/images/producto-placeholder.png',
            'variante_id' => $varianteId,
        ];

        session()->put('carrito', $carrito);

        return ['exito' => true, 'mensaje' => 'Producto agregado al carrito con éxito.'];
    }

    /**
     * Actualiza la cantidad de un ítem en el carrito.
     */
    public static function actualizarCantidad(string $clave, int $cantidad): array
    {
        $carrito = session()->get('carrito', []);
        if (!isset($carrito[$clave])) {
            return ['exito' => false, 'mensaje' => 'El ítem no existe en el carrito.'];
        }

        if ($cantidad <= 0) {
            unset($carrito[$clave]);
            session()->put('carrito', $carrito);
            return ['exito' => true, 'mensaje' => 'Producto eliminado del carrito.'];
        }

        $producto = Producto::find($carrito[$clave]['producto_id']);
        if ($producto && $cantidad > $producto->stock_actual) {
            return [
                'exito' => false,
                'mensaje' => "Stock insuficiente. Máximo {$producto->stock_actual} unidades."
            ];
        }

        $carrito[$clave]['cantidad'] = $cantidad;
        session()->put('carrito', $carrito);

        return ['exito' => true, 'mensaje' => 'Carrito actualizado.'];
    }

    /**
     * Elimina un ítem del carrito.
     */
    public static function eliminar(string $clave): void
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$clave]);
        session()->put('carrito', $carrito);
    }

    /**
     * Vacía el carrito.
     */
    public static function vaciar(): void
    {
        session()->forget(['carrito', 'cupon_aplicado']);
    }

    /**
     * Recalcula montos (subtotal, descuento de cupón, envío, total) de forma segura en backend.
     */
    public static function calcularTotales(?int $zonaEnvioId = null, string $modalidad = 'domicilio'): array
    {
        $carrito = static::obtenerCarrito();
        $subtotal = 0.00;
        $totalItems = 0;

        foreach ($carrito as $item) {
            $subtotal += ($item['precio'] * $item['cantidad']);
            $totalItems += $item['cantidad'];
        }

        // Aplicar cupón de sesión si existe
        $descuento = 0.00;
        $codigoCupon = session()->get('cupon_aplicado');
        $cupon = null;
        if ($codigoCupon) {
            $cupon = Cupon::where('codigo', $codigoCupon)->first();
            if ($cupon && $cupon->esValido($subtotal)) {
                $descuento = $cupon->calcularDescuento($subtotal);
            } else {
                session()->forget('cupon_aplicado');
                $codigoCupon = null;
            }
        }

        // Cálculo de costo de envío
        $costoEnvio = 0.00;
        if ($modalidad === 'domicilio' && $zonaEnvioId) {
            $zona = ZonaEnvio::find($zonaEnvioId);
            if ($zona) {
                if ($zona->monto_minimo_envio_gratis && ($subtotal - $descuento) >= $zona->monto_minimo_envio_gratis) {
                    $costoEnvio = 0.00;
                } else {
                    $costoEnvio = (float) $zona->costo_envio;
                }
            }
        }

        $total = max(0.00, ($subtotal - $descuento) + $costoEnvio);

        return [
            'total_items' => $totalItems,
            'subtotal' => round($subtotal, 2),
            'descuento' => round($descuento, 2),
            'costo_envio' => round($costoEnvio, 2),
            'total' => round($total, 2),
            'cupon_codigo' => $codigoCupon,
        ];
    }
}
