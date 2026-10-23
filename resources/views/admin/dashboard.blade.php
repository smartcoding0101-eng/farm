@extends('layouts.admin')

@section('titulo', 'Dashboard - NovaStore Admin')

@section('contenido')
<div>
    <!-- TARJETAS DE KPIS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <div class="card" style="border-left: 4px solid #10B981;">
            <span style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Ventas Cobradas</span>
            <div style="font-size: 1.85rem; font-weight: 900; color: #0F172A; margin-top: 0.25rem;">
                Bs. {{ number_format($ventasTotales, 2) }}
            </div>
            <div style="font-size: 0.78rem; color: #10B981; margin-top: 0.5rem;"><i class="fa-solid fa-arrow-up"></i> Pagos verificados en firme</div>
        </div>

        <div class="card" style="border-left: 4px solid #F59E0B;">
            <span style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Pagos por Verificar</span>
            <div style="font-size: 1.85rem; font-weight: 900; color: #D97706; margin-top: 0.25rem;">
                {{ $pagosPorVerificar }}
            </div>
            <div style="font-size: 0.78rem; color: #D97706; margin-top: 0.5rem;">Comprobantes QR subidos</div>
        </div>

        <div class="card" style="border-left: 4px solid #3B82F6;">
            <span style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Pedidos Nuevos</span>
            <div style="font-size: 1.85rem; font-weight: 900; color: #2563EB; margin-top: 0.25rem;">
                {{ $pedidosPendientes }}
            </div>
            <div style="font-size: 0.78rem; color: #2563EB; margin-top: 0.5rem;">Requieren despacho o confirmación</div>
        </div>

        <div class="card" style="border-left: 4px solid #EF4444;">
            <span style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Alertas de Stock</span>
            <div style="font-size: 1.85rem; font-weight: 900; color: #EF4444; margin-top: 0.25rem;">
                {{ $stockCritico }}
            </div>
            <div style="font-size: 0.78rem; color: #EF4444; margin-top: 0.5rem;">Productos en stock mínimo</div>
        </div>

    </div>

    <!-- DOS COLUMNAS: PEDIDOS RECIENTES Y KARDEX RECIENTE -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- Pedidos Recientes -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700;">Últimos Pedidos</h3>
                <a href="{{ route('admin.reportes.ventas') }}" style="color: var(--primary); font-size: 0.85rem; font-weight: 600; text-decoration: none;">Ver todos</a>
            </div>

            @if($pedidosRecientes->isEmpty())
                <p style="color: var(--text-muted); font-size: 0.88rem; text-align: center; padding: 2rem 0;">Aún no se han registrado pedidos.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Nro. Pedido</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidosRecientes as $p)
                            <tr>
                                <td style="font-weight: 700;">{{ $p->numero_pedido }}</td>
                                <td>{{ $p->cliente_nombre }}</td>
                                <td style="font-weight: 700;">Bs. {{ number_format($p->total, 2) }}</td>
                                <td>
                                    <span style="background: #EFF6FF; color: var(--primary); padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                        {{ ucfirst($p->estado_pago) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Movimientos de Kardex Recientes -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700;">Últimos Movimientos de Stock</h3>
                <a href="{{ route('admin.reportes.movimientos') }}" style="color: var(--primary); font-size: 0.85rem; font-weight: 600; text-decoration: none;">Ver Kardex</a>
            </div>

            @if($ultimosMovimientos->isEmpty())
                <p style="color: var(--text-muted); font-size: 0.88rem; text-align: center; padding: 2rem 0;">Sin movimientos de inventario registrados.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Stock Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimosMovimientos as $m)
                            <tr>
                                <td>{{ Str::limit($m->producto->nombre ?? 'N/A', 22) }}</td>
                                <td>
                                    @if($m->tipo === 'entrada')
                                        <span style="color: #059669; font-weight: 700;"><i class="fa-solid fa-arrow-down"></i> Entrada</span>
                                    @else
                                        <span style="color: #DC2626; font-weight: 700;"><i class="fa-solid fa-arrow-up"></i> Salida</span>
                                    @endif
                                </td>
                                <td style="font-weight: 700;">{{ $m->cantidad }}</td>
                                <td style="font-weight: 600;">{{ $m->stock_posterior }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>
@endsection
