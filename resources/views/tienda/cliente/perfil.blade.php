@extends('layouts.tienda')

@section('titulo', 'Mi Cuenta & Pedidos - NovaStore Bolivia')

@section('contenido')
<div class="container" style="padding-top: 2rem; padding-bottom: 5rem;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 800; margin: 0 0 0.25rem 0;">Mi Cuenta</h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.95rem;">Bienvenido(a), <strong>{{ $usuario->name }}</strong></p>
        </div>
        <form action="{{ route('cliente.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-outline" style="color: #DC2626; border-color: #FECACA;">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión
            </button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start;">
        
        <!-- DATOS DEL PERFIL -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-top: 0; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                Mis Datos
            </h3>
            <div style="font-size: 0.88rem; line-height: 1.8;">
                <div><span style="color: var(--text-muted);">Nombre:</span><br><strong>{{ $usuario->name }}</strong></div>
                <div style="margin-top: 0.5rem;"><span style="color: var(--text-muted);">Email:</span><br><strong>{{ $usuario->email }}</strong></div>
                <div style="margin-top: 0.5rem;"><span style="color: var(--text-muted);">Teléfono:</span><br><strong>{{ $usuario->telefono ?? 'No registrado' }}</strong></div>
            </div>
        </div>

        <!-- HISTORIAL DE COMPRAS -->
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.15rem; font-weight: 700; margin-top: 0; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                Historial de Mis Compras
            </h3>

            @if($pedidos->isEmpty())
                <div style="text-align: center; padding: 3rem 1rem;">
                    <i class="fa-solid fa-bag-shopping" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem;"></i>
                    <h4 style="margin: 0 0 0.5rem 0;">Aún no tienes pedidos registrados</h4>
                    <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 1.5rem;">Explora nuestras ofertas y haz tu primer pedido con pago por código QR.</p>
                    <a href="{{ route('tienda.catalogo') }}" class="btn-primary">Ver Catálogo</a>
                </div>
            @else
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color); text-align: left; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase;">
                            <th style="padding: 0.75rem 0;">Nro. Pedido</th>
                            <th style="padding: 0.75rem;">Fecha</th>
                            <th style="padding: 0.75rem;">Total</th>
                            <th style="padding: 0.75rem;">Estado Pedido</th>
                            <th style="padding: 0.75rem;">Estado Pago</th>
                            <th style="padding: 0.75rem; text-align: right;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $p)
                            <tr style="border-bottom: 1px solid var(--border-color); font-size: 0.88rem;">
                                <td style="padding: 1rem 0; font-weight: 700;">{{ $p->numero_pedido }}</td>
                                <td style="padding: 1rem; color: var(--text-muted);">{{ $p->created_at->format('d/m/Y') }}</td>
                                <td style="padding: 1rem; font-weight: 800;">Bs. {{ number_format($p->total, 2) }}</td>
                                <td style="padding: 1rem;">
                                    <span style="background: #F1F5F9; color: #475569; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                        {{ $p->estado_pedido }}
                                    </span>
                                </td>
                                <td style="padding: 1rem;">
                                    @if($p->estado_pago === 'pagado')
                                        <span style="background: #ECFDF5; color: #065F46; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                                            PAGADO
                                        </span>
                                    @else
                                        <span style="background: #FEF3C7; color: #92400E; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                                            {{ strtoupper(str_replace('_', ' ', $p->estado_pago)) }}
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <a href="{{ route('checkout.confirmacion', $p->numero_pedido) }}" class="btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.78rem;">
                                        Ver QR / Detalle
                                    </a>
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

    </div>

</div>
@endsection
