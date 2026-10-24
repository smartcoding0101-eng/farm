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
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="border-top: 4px solid #64748B;">
            <span style="font-size: 0.76rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Ingreso Bruto Facturable</span>
            <div style="font-size: 1.75rem; font-weight: 900; color: #0F2414; margin-top: 0.35rem;">Bs. {{ number_format($ingresoBruto, 2) }}</div>
            <div style="font-size: 0.78rem; color: #64748B; margin-top: 0.5rem;">Suma total antes de deducciones</div>
        </div>

        <div class="card" style="border-top: 4px solid #10B981; background: #F0FDF4;">
            <span style="font-size: 0.76rem; color: #065F46; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Total Cobrado en Firme</span>
            <div style="font-size: 1.75rem; font-weight: 900; color: #065F46; margin-top: 0.35rem;">Bs. {{ number_format($totalCobrado, 2) }}</div>
            <div style="font-size: 0.78rem; color: #047857; margin-top: 0.5rem;"><i class="fa-solid fa-circle-check"></i> Pagos verificados en banco</div>
        </div>

        <div class="card" style="border-top: 4px solid #EF4444;">
            <span style="font-size: 0.76rem; color: #991B1B; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Descuentos Otorgados</span>
            <div style="font-size: 1.75rem; font-weight: 900; color: #991B1B; margin-top: 0.35rem;">Bs. {{ number_format($descuentos, 2) }}</div>
            <div style="font-size: 0.78rem; color: #B91C1C; margin-top: 0.5rem;">Cupones y promociones aplicadas</div>
        </div>

        <div class="card" style="border-top: 4px solid #3B82F6;">
            <span style="font-size: 0.76rem; color: #1E40AF; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Fletes de Delivery</span>
            <div style="font-size: 1.75rem; font-weight: 900; color: #1E40AF; margin-top: 0.35rem;">Bs. {{ number_format($costosEnvio, 2) }}</div>
            <div style="font-size: 0.78rem; color: #2563EB; margin-top: 0.5rem;">Logística y cadena de frío</div>
        </div>
    </div>

    <!-- TABLA DE PEDIDOS -->
    @if($pedidos->isEmpty())
        <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted);">
            <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: #F1F5F9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: #94A3B8;">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <h3 style="margin: 0 0 0.25rem; font-size: 1.05rem; font-weight: 700; color: #1E293B;">Sin transacciones</h3>
            <p style="margin: 0; font-size: 0.88rem;">No existen pedidos registrados en este período.</p>
        </div>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Fecha / Hora</th>
                        <th>Nro. Pedido</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>Descuento</th>
                        <th>Envío</th>
                        <th>Total Cobrado</th>
                        <th>Método</th>
                        <th>Estado Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $p)
                        <tr>
                            <td>
                                <span style="color: var(--text-muted); font-size: 0.82rem; font-weight: 600;">
                                    {{ $p->created_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 800; color: var(--primary); font-family: monospace; font-size: 0.92rem;">
                                    {{ $p->numero_pedido }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #0F172A;">{{ $p->cliente_nombre }}</span>
                            </td>
                            <td>Bs. {{ number_format($p->subtotal, 2) }}</td>
                            <td>
                                @if($p->descuento > 0)
                                    <span style="color: #DC2626; font-weight: 700;">-Bs. {{ number_format($p->descuento, 2) }}</span>
                                @else
                                    <span style="color: #94A3B8;">—</span>
                                @endif
                            </td>
                            <td>Bs. {{ number_format($p->costo_envio, 2) }}</td>
                            <td>
                                <span style="font-weight: 900; color: #0F172A; font-size: 0.98rem;">
                                    Bs. {{ number_format($p->total, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-neutral" style="text-transform: uppercase;">{{ str_replace('_', ' ', $p->metodo_pago) }}</span>
                            </td>
                            <td>
                                @if($p->estado_pago === 'pagado')
                                    <span class="badge badge-success"><i class="fa-solid fa-check"></i> Pagado</span>
                                @elseif($p->estado_pago === 'en_verificacion')
                                    <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> En Verificación</span>
                                @else
                                    <span class="badge badge-neutral">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>
@endsection

