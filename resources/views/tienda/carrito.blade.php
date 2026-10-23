@extends('layouts.tienda')

@section('titulo', 'Mi Carrito de Compras - NovaStore Bolivia')

@section('contenido')
<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">

    <h1 style="font-size: 2rem; font-weight: 800; margin: 0 0 1.5rem 0;">Carrito de Compras</h1>

    @if(empty($carrito))
        <div class="card" style="padding: 4rem 2rem; text-align: center;">
            <i class="fa-solid fa-cart-shopping" style="font-size: 4rem; color: #CBD5E1; margin-bottom: 1.5rem;"></i>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 0.5rem 0;">Tu carrito está actualmente vacío</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Descubre nuestras ofertas en tecnología, moda, audio y más.</p>
            <a href="{{ route('tienda.catalogo') }}" class="btn-primary" style="padding: 0.85rem 2rem; font-size: 1rem;">
                <i class="fa-solid fa-bag-shopping"></i> Explorar Catálogo
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start;">
            
            <!-- LISTA DE PRODUCTOS EN EL CARRITO -->
            <div class="card" style="padding: 1.5rem;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color); text-align: left; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">
                            <th style="padding-bottom: 1rem;">Producto</th>
                            <th style="padding-bottom: 1rem; text-align: center;">Precio</th>
                            <th style="padding-bottom: 1rem; text-align: center;">Cantidad</th>
                            <th style="padding-bottom: 1rem; text-align: right;">Subtotal</th>
                            <th style="padding-bottom: 1rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carrito as $clave => $item)
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 1.25rem 0; display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 60px; height: 60px; border-radius: 0.5rem; background: #F1F5F9; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-box" style="color: #94A3B8;"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('tienda.producto', $item['slug']) }}" style="font-weight: 700; color: inherit; text-decoration: none; font-size: 0.95rem;">
                                            {{ $item['nombre'] }}
                                        </a>
                                        <div style="font-size: 0.78rem; color: var(--text-muted);">SKU: {{ $item['sku'] }}</div>
                                    </div>
                                </td>
                                <td style="padding: 1.25rem 0.5rem; text-align: center; font-weight: 600;">
                                    Bs. {{ number_format($item['precio'], 2) }}
                                </td>
                                <td style="padding: 1.25rem 0.5rem; text-align: center;">
                                    <form action="{{ route('carrito.actualizar') }}" method="POST" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                        @csrf
                                        <input type="hidden" name="clave" value="{{ $clave }}">
                                        <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="99" style="width: 60px; padding: 0.35rem; border: 1px solid var(--border-color); border-radius: 0.35rem; text-align: center;" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td style="padding: 1.25rem 0.5rem; text-align: right; font-weight: 800; color: #0F172A;">
                                    Bs. {{ number_format($item['precio'] * $item['cantidad'], 2) }}
                                </td>
                                <td style="padding: 1.25rem 0; text-align: right;">
                                    <a href="{{ route('carrito.eliminar', $clave) }}" style="color: #EF4444; text-decoration: none; padding: 0.5rem;" title="Eliminar ítem">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                    <a href="{{ route('tienda.catalogo') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                        <i class="fa-solid fa-arrow-left"></i> Continuar Comprando
                    </a>
                </div>
            </div>

            <!-- RESUMEN DE COMPRA Y CUPÓN -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <!-- Cupón de descuento -->
                <div class="card" style="padding: 1.25rem;">
                    <h3 style="font-size: 0.95rem; font-weight: 700; margin-top: 0; margin-bottom: 0.75rem;">¿Tienes un cupón?</h3>
                    @if(session('cupon_aplicado'))
                        <div style="display: flex; justify-content: space-between; align-items: center; background: #ECFDF5; border: 1px solid #A7F3D0; padding: 0.6rem 0.85rem; border-radius: 0.5rem;">
                            <span style="font-weight: 700; color: #065F46; font-size: 0.85rem;">{{ session('cupon_aplicado') }}</span>
                            <a href="{{ route('carrito.cupon.remover') }}" style="color: #EF4444; font-size: 0.8rem; text-decoration: none; font-weight: 600;">Remover</a>
                        </div>
                    @else
                        <form action="{{ route('carrito.cupon') }}" method="POST" style="display: flex; gap: 0.5rem;">
                            @csrf
                            <input type="text" name="codigo" placeholder="Ej: BIENVENIDO10" style="flex: 1; padding: 0.55rem 0.75rem; border: 1px solid var(--border-color); border-radius: 0.4rem; font-size: 0.88rem; text-transform: uppercase;">
                            <button type="submit" class="btn-outline" style="padding: 0.55rem 0.85rem; font-size: 0.85rem;">Aplicar</button>
                        </form>
                    @endif
                </div>

                <!-- Desglose de totales -->
                <div class="card" style="padding: 1.5rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                        Resumen del Pedido
                    </h3>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.92rem;">
                        <span style="color: var(--text-muted);">Subtotal ({{ $totales['total_items'] }} productos)</span>
                        <span style="font-weight: 600;">Bs. {{ number_format($totales['subtotal'], 2) }}</span>
                    </div>

                    @if($totales['descuento'] > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.92rem; color: #059669;">
                            <span>Descuento de Cupón</span>
                            <span style="font-weight: 700;">- Bs. {{ number_format($totales['descuento'], 2) }}</span>
                        </div>
                    @endif

                    <div style="display: flex; justify-content: space-between; margin-bottom: 1.25rem; font-size: 0.92rem;">
                        <span style="color: var(--text-muted);">Envío estimado</span>
                        <span style="font-size: 0.85rem; color: #64748B;">Calculado en el checkout</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: baseline; border-top: 2px solid var(--border-color); padding-top: 1rem; margin-bottom: 1.5rem;">
                        <span style="font-size: 1.1rem; font-weight: 800;">Total</span>
                        <span style="font-size: 1.6rem; font-weight: 900; color: var(--primary);">Bs. {{ number_format($totales['total'], 2) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn-primary" style="width: 100%; justify-content: center; padding: 0.9rem; font-size: 1rem;">
                        <i class="fa-solid fa-lock"></i> Proceder al Checkout
                    </a>
                </div>

            </div>

        </div>
    @endif

</div>
@endsection
