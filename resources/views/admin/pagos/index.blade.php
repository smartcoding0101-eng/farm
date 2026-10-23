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
        <p style="text-align: center; color: var(--text-muted); padding: 3rem 0;">No hay pagos registrados pendientes de revisión.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Nro. Pedido</th>
                    <th>Referencia</th>
                    <th>Método</th>
                    <th>Monto</th>
                    <th>Comprobante</th>
                    <th>Estado Actual</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagos as $pago)
                    <tr>
                        <td style="color: var(--text-muted); font-size: 0.82rem;">{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                        <td style="font-weight: 700;">{{ $pago->pedido->numero_pedido }}</td>
                        <td><code>{{ $pago->referencia_pago }}</code></td>
                        <td><span style="text-transform: uppercase; font-weight: 600;">{{ $pago->metodo }}</span></td>
                        <td style="font-weight: 800; color: #0F172A;">Bs. {{ number_format($pago->monto, 2) }}</td>
                        <td>
                            @if($pago->comprobante_archivo)
                                <a href="{{ asset('storage/' . $pago->comprobante_archivo) }}" target="_blank" style="color: var(--primary); font-weight: 600; text-decoration: none;">
                                    <i class="fa-solid fa-paperclip"></i> Ver Archivo
                                </a>
                            @else
                                <span style="color: #94A3B8; font-size: 0.82rem;">Sin comprobante</span>
                            @endif
                        </td>
                        <td>
                            @if($pago->estado === 'aprobado')
                                <span style="background: #ECFDF5; color: #065F46; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 700; font-size: 0.75rem;">
                                    APROBADO
                                </span>
                            @elseif($pago->estado === 'en_revision')
                                <span style="background: #FEF3C7; color: #92400E; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 700; font-size: 0.75rem;">
                                    POR VERIFICAR
                                </span>
                            @elseif($pago->estado === 'rechazado')
                                <span style="background: #FEF2F2; color: #991B1B; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 700; font-size: 0.75rem;">
                                    RECHAZADO
                                </span>
                            @else
                                <span style="background: #F1F5F9; color: #475569; padding: 0.2rem 0.6rem; border-radius: 9999px; font-weight: 600; font-size: 0.75rem;">
                                    PENDIENTE
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($pago->estado !== 'aprobado')
                                <form action="{{ route('admin.pagos.estado', $pago->id) }}" method="POST" style="display: inline-flex; gap: 0.35rem;">
                                    @csrf
                                    <input type="hidden" name="estado" value="aprobado">
                                    <button type="submit" class="btn-success" title="Aprobar pago y confirmar pedido">
                                        <i class="fa-solid fa-check"></i> Aprobar
                                    </button>
                                </form>
                                <form action="{{ route('admin.pagos.estado', $pago->id) }}" method="POST" style="display: inline-flex; gap: 0.35rem;">
                                    @csrf
                                    <input type="hidden" name="estado" value="rechazado">
                                    <button type="submit" class="btn-danger" title="Rechazar pago" onclick="return confirm('¿Rechazar este pago?')">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            @else
                                <span style="color: #059669; font-size: 0.82rem;"><i class="fa-solid fa-circle-check"></i> Validado</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 1.5rem;">
            {{ $pagos->links() }}
        </div>
    @endif
</div>
@endsection
