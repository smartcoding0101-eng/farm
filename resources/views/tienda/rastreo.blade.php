@extends('layouts.tienda')

@section('titulo', 'Rastreo y Seguimiento de Pedidos - NovaStore Bolivia')

@section('contenido')
<div class="container" style="padding-top: 3rem; padding-bottom: 5rem; max-width: 800px;">

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 2rem; font-weight: 800; margin: 0 0 0.5rem 0;">Seguimiento de Pedido</h1>
        <p style="color: var(--text-muted); margin: 0;">Ingresa el código único de tu compra (ej: <code>PED-20261022-XXXXX</code>) para consultar el estado del pago y despacho.</p>
    </div>

    <!-- FORMULARIO DE CONSULTA -->
    <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
        <form action="{{ route('tienda.rastreo') }}" method="GET" style="display: flex; gap: 0.75rem;">
            <input type="text" name="numero_pedido" value="{{ request('numero_pedido') }}" required placeholder="Código de Pedido (PED-...)" style="flex: 1; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 0.5rem; font-size: 1rem; text-transform: uppercase;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 1.75rem; font-size: 1rem;">
                <i class="fa-solid fa-magnifying-glass"></i> Consultar
            </button>
        </form>
    </div>

    @if(request()->filled('numero_pedido') && !$pedido)
        <div class="card" style="padding: 2.5rem; text-align: center; border-left: 4px solid #EF4444;">
            <i class="fa-solid fa-circle-xmark" style="font-size: 3rem; color: #EF4444; margin-bottom: 1rem;"></i>
            <h3 style="margin: 0 0 0.5rem 0;">No encontramos el pedido indicado</h3>
            <p style="color: var(--text-muted); margin: 0;">Verifica que el código ingresado coincida exactamente con el de tu recibo de compra.</p>
        </div>
    @elseif($pedido)
        <!-- DETALLES DEL ESTADO DEL PEDIDO -->
        <div class="card" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 0.25rem 0;">Pedido: {{ $pedido->numero_pedido }}</h2>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Generado el {{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; font-weight: 900; color: var(--primary);">Bs. {{ number_format($pedido->total, 2) }}</div>
                    <span style="font-size: 0.82rem; color: var(--text-muted);">Método: {{ strtoupper($pedido->metodo_pago) }}</span>
                </div>
            </div>

            <!-- LÍNEA DE TIEMPO / ESTADO LOGÍSTICO -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; text-align: center;">
                <div style="padding: 0.75rem; border-radius: 0.5rem; background: #ECFDF5; border: 1px solid #A7F3D0;">
                    <i class="fa-solid fa-file-invoice" style="color: #059669; font-size: 1.25rem; margin-bottom: 0.25rem;"></i>
                    <div style="font-size: 0.82rem; font-weight: 700; color: #065F46;">Registrado</div>
                </div>

                <div style="padding: 0.75rem; border-radius: 0.5rem; background: {{ in_array($pedido->estado_pago, ['pagado']) ? '#ECFDF5' : '#FEF3C7' }}; border: 1px solid {{ in_array($pedido->estado_pago, ['pagado']) ? '#A7F3D0' : '#FDE68A' }};">
                    <i class="fa-solid fa-qrcode" style="color: {{ in_array($pedido->estado_pago, ['pagado']) ? '#059669' : '#D97706' }}; font-size: 1.25rem; margin-bottom: 0.25rem;"></i>
                    <div style="font-size: 0.82rem; font-weight: 700; color: {{ in_array($pedido->estado_pago, ['pagado']) ? '#065F46' : '#92400E' }};">
                        Pago {{ ucfirst($pedido->estado_pago) }}
                    </div>
                </div>

                <div style="padding: 0.75rem; border-radius: 0.5rem; background: {{ in_array($pedido->estado_pedido, ['en_preparacion', 'listo_despacho', 'en_camino', 'entregado']) ? '#ECFDF5' : '#F1F5F9' }};">
                    <i class="fa-solid fa-box-open" style="color: {{ in_array($pedido->estado_pedido, ['en_preparacion', 'listo_despacho', 'en_camino', 'entregado']) ? '#059669' : '#94A3B8' }}; font-size: 1.25rem; margin-bottom: 0.25rem;"></i>
                    <div style="font-size: 0.82rem; font-weight: 700;">En Preparación</div>
                </div>

                <div style="padding: 0.75rem; border-radius: 0.5rem; background: {{ $pedido->estado_pedido === 'entregado' ? '#ECFDF5' : '#F1F5F9' }};">
                    <i class="fa-solid fa-truck-fast" style="color: {{ $pedido->estado_pedido === 'entregado' ? '#059669' : '#94A3B8' }}; font-size: 1.25rem; margin-bottom: 0.25rem;"></i>
                    <div style="font-size: 0.82rem; font-weight: 700;">Despacho</div>
                </div>
            </div>

            <!-- DATOS DE ENTREGA -->
            <div style="background: #F8FAFC; padding: 1.25rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.88rem; line-height: 1.6;">
                <div><strong>Cliente:</strong> {{ $pedido->cliente_nombre }} (Tel: {{ $pedido->cliente_telefono }})</div>
                <div><strong>Modalidad:</strong> {{ $pedido->modalidad_entrega === 'domicilio' ? 'Entrega a Domicilio' : 'Retiro en Tienda' }}</div>
                <div><strong>Dirección:</strong> {{ $pedido->direccion_envio }}</div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('checkout.confirmacion', $pedido->numero_pedido) }}" class="btn-primary" style="padding: 0.75rem 1.5rem;">
                    <i class="fa-solid fa-qrcode"></i> Ver Pantalla de Pago y Subir Comprobante
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
