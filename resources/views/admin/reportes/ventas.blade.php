@extends('layouts.admin')

@section('titulo', 'Reporte de Ventas y Finanzas - NovaStore Admin')

@section('contenido')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.35rem 0;">Reporte de Ventas y Conciliación Financiera</h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.88rem;">Detalle de transacciones cobradas, descuentos aplicados, fletes y métodos de pago.</p>
        </div>
    </div>

    <!-- TARJETAS FINANCIERAS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1.25rem;">
            <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Ingreso Bruto</span>
            <div style="font-size: 1.5rem; font-weight: 800; margin-top: 0.25rem;">Bs. {{ number_format($ingresoBruto, 2) }}</div>
        </div>

        <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 0.5rem; padding: 1.25rem;">
            <span style="font-size: 0.8rem; color: #065F46; text-transform: uppercase; font-weight: 600;">Total Cobrado en Firme</span>
            <div style="font-size: 1.5rem; font-weight: 900; color: #065F46; margin-top: 0.25rem;">Bs. {{ number_format($totalCobrado, 2) }}</div>
        </div>

        <div style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 0.5rem; padding: 1.25rem;">
            <span style="font-size: 0.8rem; color: #991B1B; text-transform: uppercase; font-weight: 600;">Descuentos Otorgados</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #991B1B; margin-top: 0.25rem;">Bs. {{ number_format($descuentos, 2) }}</div>
        </div>

        <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1.25rem;">
            <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Fletes / Envíos</span>
            <div style="font-size: 1.5rem; font-weight: 800; margin-top: 0.25rem;">Bs. {{ number_format($costosEnvio, 2) }}</div>
        </div>
    </div>

    <!-- TABLA DE PEDIDOS -->
    @if($pedidos->isEmpty())
        <p style="text-align: center; color: var(--text-muted); padding: 3rem 0;">No existen pedidos registrados en este período.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Nro. Pedido</th>
                    <th>Cliente</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Envío</th>
                    <th>Total</th>
                    <th>Método</th>
                    <th>Estado Pago</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $p)
                    <tr>
                        <td style="color: var(--text-muted); font-size: 0.82rem;">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td style="font-weight: 700;">{{ $p->numero_pedido }}</td>
                        <td>{{ $p->cliente_nombre }}</td>
                        <td>Bs. {{ number_format($p->subtotal, 2) }}</td>
                        <td style="color: #DC2626;">- Bs. {{ number_format($p->descuento, 2) }}</td>
                        <td>Bs. {{ number_format($p->costo_envio, 2) }}</td>
                        <td style="font-weight: 800; color: #0F172A;">Bs. {{ number_format($p->total, 2) }}</td>
                        <td><span style="font-weight: 600; text-transform: uppercase; font-size: 0.8rem;">{{ str_replace('_', ' ', $p->metodo_pago) }}</span></td>
                        <td>
                            @if($p->estado_pago === 'pagado')
                                <span style="background: #ECFDF5; color: #065F46; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                                    PAGADO
                                </span>
                            @elseif($p->estado_pago === 'en_verificacion')
                                <span style="background: #FEF3C7; color: #92400E; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                                    EN REVISIÓN
                                </span>
                            @else
                                <span style="background: #F1F5F9; color: #475569; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    PENDIENTE
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 1.5rem;">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>
@endsection
