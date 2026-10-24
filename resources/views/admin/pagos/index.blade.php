@extends('layouts.admin')

@section('titulo', 'Gestión de Pagos QR y Transferencias - Admin')

@section('contenido')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.35rem 0;">Validación de Pagos QR y Transferencias</h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.88rem;">Revisa los comprobantes subidos por los clientes y confirma las transacciones antes del despacho.</p>
        </div>
    </div>

    @if($pagos->isEmpty())
        <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted);">
            <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: #FFFBEB; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: #D97706;">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <h3 style="margin: 0 0 0.25rem; font-size: 1.05rem; font-weight: 700; color: #1E293B;">Al día con las verificaciones</h3>
            <p style="margin: 0; font-size: 0.88rem;">No existen transferencias ni comprobantes QR pendientes de revisión.</p>
        </div>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Fecha / Hora</th>
                        <th>Nro. Pedido</th>
                        <th>Referencia</th>
                        <th>Método</th>
                        <th>Monto a Cobrar</th>
                        <th>Comprobante</th>
                        <th>Estado Actual</th>
                        <th style="text-align: right;">Validación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                        <tr>
                            <td>
                                <span style="color: var(--text-muted); font-size: 0.82rem; font-weight: 600;">
                                    <i class="fa-regular fa-calendar" style="color: #94A3B8;"></i> {{ $pago->created_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 800; color: var(--primary); font-family: monospace; font-size: 0.92rem;">
                                    {{ $pago->pedido->numero_pedido }}
                                </span>
                            </td>
                            <td>
                                <code style="background: #F1F5F9; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; color: #475569;">
                                    {{ $pago->referencia_pago ?? 'S/R' }}
                                </code>
                            </td>
                            <td>
                                <span class="badge badge-neutral" style="text-transform: uppercase;">
                                    <i class="fa-solid fa-building-columns"></i> {{ $pago->metodo }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 900; color: #0F172A; font-size: 1rem;">
                                    Bs. {{ number_format($pago->monto, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($pago->comprobante_archivo)
                                    <a href="{{ asset('storage/' . $pago->comprobante_archivo) }}" target="_blank" class="btn-outline" style="padding: 0.3rem 0.65rem; font-size: 0.78rem;">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver Captura
                                    </a>
                                @else
                                    <span style="color: #94A3B8; font-size: 0.8rem;">Sin adjunto</span>
                                @endif
                            </td>
                            <td>
                                @if($pago->estado === 'aprobado')
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> APROBADO</span>
                                @elseif($pago->estado === 'en_revision')
                                    <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> POR VERIFICAR</span>
                                @elseif($pago->estado === 'rechazado')
                                    <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> RECHAZADO</span>
                                @else
                                    <span class="badge badge-neutral">{{ strtoupper($pago->estado) }}</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($pago->estado !== 'aprobado')
                                    <div style="display: inline-flex; gap: 0.4rem; justify-content: flex-end;">
                                        <form action="{{ route('admin.pagos.estado', $pago->id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <input type="hidden" name="estado" value="aprobado">
                                            <button type="submit" class="btn-success" title="Aprobar pago">
                                                <i class="fa-solid fa-check"></i> Aprobar
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pagos.estado', $pago->id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <input type="hidden" name="estado" value="rechazado">
                                            <button type="submit" class="btn-danger" title="Rechazar pago" onclick="return confirm('¿Rechazar este pago?')">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge badge-success" style="font-size: 0.8rem;"><i class="fa-solid fa-check-double"></i> Conciliado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $pagos->links() }}
        </div>
    @endif

</div>
@endsection
