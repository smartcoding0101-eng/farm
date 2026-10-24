@extends('layouts.admin')

@section('titulo', 'Dashboard Gerencial - FARMALUZ Admin')

@section('contenido')
<div style="display: flex; flex-direction: column; gap: 2rem;">
    <!-- ENCABEZADO DE BIENVENIDA -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem;">
        <div>
            <span style="font-size: 0.8rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.08em; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-circle" style="font-size: 0.5rem; color: #10B981;"></i> Sistema En Línea
            </span>
            <h1 style="font-size: 1.85rem; font-weight: 800; margin: 0.35rem 0 0 0; color: #0F2414; letter-spacing: -0.02em;">
                Panel de Control Farmacéutico
            </h1>
            <p style="color: var(--text-muted); margin: 0.35rem 0 0 0; font-size: 0.92rem;">
                Supervisión de fórmulas magistrales, recaudación en Bolivianos y rotación de fitoterapéuticos.
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="{{ route('tienda.catalogo.pdf') }}" target="_blank" class="btn-outline">
                <i class="fa-solid fa-file-pdf" style="color: #DC2626;"></i> Catálogo PDF
            </a>
            <a href="{{ route('admin.productos.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Registrar Producto
            </a>
        </div>
    </div>

    <!-- TARJETAS DE KPIS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
        
        <!-- KPI 1 -->
        <div class="card" style="position: relative; overflow: hidden; border-top: 4px solid #10B981;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.76rem; font-weight: 800; color: #52796F; text-transform: uppercase; letter-spacing: 0.05em;">Ventas Cobradas</span>
                    <div style="font-size: 1.95rem; font-weight: 900; color: #0F2414; margin-top: 0.4rem; letter-spacing: -0.02em;">
                        Bs. {{ number_format($ventasTotales, 2) }}
                    </div>
                </div>
                <div style="width: 46px; height: 46px; border-radius: var(--radius-sm); background: #ECFDF5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: #059669; font-weight: 700; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px dashed var(--border-color);">
                <i class="fa-solid fa-shield-check"></i> Fondos verificados en caja
            </div>
        </div>

        <!-- KPI 2 -->
        <div class="card" style="position: relative; overflow: hidden; border-top: 4px solid #F59E0B;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.76rem; font-weight: 800; color: #52796F; text-transform: uppercase; letter-spacing: 0.05em;">Pagos por Verificar</span>
                    <div style="font-size: 1.95rem; font-weight: 900; color: #D97706; margin-top: 0.4rem; letter-spacing: -0.02em;">
                        {{ $pagosPorVerificar }}
                    </div>
                </div>
                <div style="width: 46px; height: 46px; border-radius: var(--radius-sm); background: #FFFBEB; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: #D97706; font-weight: 700; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px dashed var(--border-color);">
                <a href="{{ route('admin.pagos.index') }}" style="color: inherit; text-decoration: none;">Comprobantes QR pendientes <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- KPI 3 -->
        <div class="card" style="position: relative; overflow: hidden; border-top: 4px solid #3B82F6;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.76rem; font-weight: 800; color: #52796F; text-transform: uppercase; letter-spacing: 0.05em;">Pedidos Pendientes</span>
                    <div style="font-size: 1.95rem; font-weight: 900; color: #2563EB; margin-top: 0.4rem; letter-spacing: -0.02em;">
                        {{ $pedidosPendientes }}
                    </div>
                </div>
                <div style="width: 46px; height: 46px; border-radius: var(--radius-sm); background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: #2563EB; font-weight: 700; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px dashed var(--border-color);">
                <span>Por empaque y despacho</span>
            </div>
        </div>

        <!-- KPI 4 -->
        <div class="card" style="position: relative; overflow: hidden; border-top: 4px solid #EF4444;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.76rem; font-weight: 800; color: #52796F; text-transform: uppercase; letter-spacing: 0.05em;">Alertas de Stock</span>
                    <div style="font-size: 1.95rem; font-weight: 900; color: #EF4444; margin-top: 0.4rem; letter-spacing: -0.02em;">
                        {{ $stockCritico }}
                    </div>
                </div>
                <div style="width: 46px; height: 46px; border-radius: var(--radius-sm); background: #FEF2F2; color: #EF4444; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: #EF4444; font-weight: 700; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px dashed var(--border-color);">
                <a href="{{ route('admin.productos.index') }}" style="color: inherit; text-decoration: none;">Artículos en nivel crítico <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

    </div>

    <!-- DOS COLUMNAS DE ALTO IMPACTO -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 1.75rem;">
        
        <!-- Pedidos Recientes -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <h2 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0F2414;">Últimos Pedidos</h2>
                </div>
                <a href="{{ route('admin.reportes.ventas') }}" class="btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.78rem;">
                    Ver todos <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            @if($pedidosRecientes->isEmpty())
                <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                    <div style="width: 56px; height: 56px; margin: 0 auto 0.75rem; background: #F1F5F9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #94A3B8;">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <h3 style="margin: 0 0 0.25rem; font-size: 0.95rem; font-weight: 700; color: #1E293B;">Sin pedidos recientes</h3>
                    <p style="margin: 0; font-size: 0.82rem;">Los nuevos pedidos generados desde la tienda aparecerán aquí en tiempo real.</p>
                </div>
            @else
                <div class="table-responsive">
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
                                    <td>
                                        <span style="font-weight: 800; color: var(--primary); font-family: monospace; font-size: 0.92rem;">{{ $p->numero_pedido }}</span>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div style="width: 26px; height: 26px; border-radius: 50%; background: #E2ECE2; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800;">
                                                {{ strtoupper(substr($p->cliente_nombre, 0, 1)) }}
                                            </div>
                                            <span style="font-weight: 600;">{{ $p->cliente_nombre }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-weight: 900; color: #0F172A;">Bs. {{ number_format($p->total, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($p->estado_pago === 'pagado')
                                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> Pagado</span>
                                        @elseif($p->estado_pago === 'en_verificacion')
                                            <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> En Verificación</span>
                                        @else
                                            <span class="badge badge-neutral"><i class="fa-solid fa-hourglass-start"></i> Pendiente</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Movimientos de Kardex Recientes -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: #FDF2F8; color: var(--secondary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fa-solid fa-warehouse"></i>
                    </div>
                    <h2 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0F2414;">Auditoría Kardex</h2>
                </div>
                <a href="{{ route('admin.reportes.movimientos') }}" class="btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.78rem;">
                    Ver Kardex <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            @if($ultimosMovimientos->isEmpty())
                <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                    <div style="width: 56px; height: 56px; margin: 0 auto 0.75rem; background: #F1F5F9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #94A3B8;">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <h3 style="margin: 0 0 0.25rem; font-size: 0.95rem; font-weight: 700; color: #1E293B;">Sin movimientos aún</h3>
                    <p style="margin: 0; font-size: 0.82rem;">Las entradas y salidas de stock quedarán auditadas aquí.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Operación</th>
                                <th>Cantidad</th>
                                <th>Stock Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosMovimientos as $m)
                                <tr>
                                    <td>
                                        <span style="font-weight: 700; color: #0F172A;" title="{{ $m->producto->nombre ?? 'N/A' }}">
                                            {{ Str::limit($m->producto->nombre ?? 'N/A', 26) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($m->tipo === 'entrada')
                                            <span class="badge badge-success"><i class="fa-solid fa-arrow-down"></i> Entrada</span>
                                        @else
                                            <span class="badge badge-danger"><i class="fa-solid fa-arrow-up"></i> Salida</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-weight: 800; color: #0F172A;">{{ $m->cantidad }} u.</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-neutral" style="font-weight: 700;">{{ $m->stock_posterior }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

