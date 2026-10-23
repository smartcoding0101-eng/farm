@extends('layouts.tienda')

@section('titulo', $producto->nombre . ' - NovaStore Bolivia')

@section('contenido')
<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">

    <nav style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem;">
        <a href="{{ route('tienda.inicio') }}" style="color: inherit; text-decoration: none;">Inicio</a>
        <span style="margin: 0 0.5rem;">/</span>
        <a href="{{ route('tienda.catalogo', ['categoria' => $producto->categoria->slug]) }}" style="color: inherit; text-decoration: none;">{{ $producto->categoria->nombre }}</a>
        <span style="margin: 0 0.5rem;">/</span>
        <span style="color: var(--text-main); font-weight: 600;">{{ $producto->nombre }}</span>
    </nav>

    <div class="card" style="padding: 2.5rem; margin-bottom: 3rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3rem;">
            
            <!-- IMAGEN PRINCIPAL -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 1rem; min-height: 400px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; padding: 1.5rem;">
                @if($producto->imagen_principal)
                    <img src="{{ $producto->imagen_principal }}" alt="{{ $producto->nombre }}" style="max-width: 100%; max-height: 380px; object-fit: contain; border-radius: 0.5rem; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                @else
                    <i class="fa-solid fa-mortar-pestle" style="font-size: 6rem; color: #E4007C;"></i>
                @endif
                @if($producto->tieneOfertaActiva())
                    <span style="position: absolute; top: 1.5rem; right: 1.5rem; background: var(--secondary); color: white; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; box-shadow: 0 4px 10px rgba(228,0,124,0.3);">EN OFERTA</span>
                @endif
            </div>

            <!-- DETALLES Y COMPRA -->
            <div style="display: flex; flex-direction: column;">
                <span style="color: var(--primary); font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                    {{ $producto->marca->nombre ?? 'Marca Oficial' }}
                </span>
                <h1 style="font-size: 2rem; font-weight: 800; line-height: 1.25; margin: 0 0 1rem 0;">
                    {{ $producto->nombre }}
                </h1>
                
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem; display: flex; gap: 1.5rem;">
                    <span><strong>SKU:</strong> {{ $producto->sku }}</span>
                    <span><strong>Disponibilidad:</strong> <span style="color: #059669; font-weight: 600;">{{ $producto->stock_actual }} unidades</span></span>
                </div>

                <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1.5rem;">
                    @if($producto->tieneOfertaActiva())
                        <div style="display: flex; align-items: baseline; gap: 0.75rem;">
                            <span style="font-size: 2.25rem; font-weight: 800; color: #EF4444;">Bs. {{ number_format($producto->precio_oferta, 2) }}</span>
                            <span style="font-size: 1.1rem; color: #94A3B8; text-decoration: line-through;">Bs. {{ number_format($producto->precio_venta, 2) }}</span>
                        </div>
                    @else
                        <span style="font-size: 2.25rem; font-weight: 800; color: #0F172A;">Bs. {{ number_format($producto->precio_venta, 2) }}</span>
                    @endif
                    <p style="margin: 0.5rem 0 0 0; font-size: 0.82rem; color: var(--text-muted);">
                        Precio final incluye impuestos de ley. Pago mediante QR o transferencia.
                    </p>
                </div>

                <form action="{{ route('carrito.agregar') }}" method="POST" style="margin-bottom: 2rem;">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="width: 100px;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.3rem;">Cantidad</label>
                            <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock_actual }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; text-align: center; font-weight: 700;">
                        </div>
                        <div style="flex: 1; padding-top: 1.3rem;">
                            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem; font-size: 1rem;">
                                <i class="fa-solid fa-cart-shopping"></i> Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </form>

                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem;">Descripción</h3>
                    <p style="font-size: 0.95rem; line-height: 1.7; color: #475569;">
                        {{ $producto->descripcion_completa ?? $producto->descripcion_corta }}
                    </p>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
