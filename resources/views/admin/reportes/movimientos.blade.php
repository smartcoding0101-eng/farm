@extends('layouts.admin')

@section('titulo', 'Reporte de Movimientos de Inventario (Kardex) - Admin')

@section('contenido')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.35rem 0;">Reporte de Movimientos de Inventario (Kardex)</h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.88rem;">Auditoría completa e inmutable de entradas, salidas y ajustes de stock con usuarios y motivos.</p>
        </div>
    </div>

    <!-- Filtros de búsqueda -->
    <form action="{{ route('admin.reportes.movimientos') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; background: #F8FAFC; padding: 1rem; border-radius: 0.5rem;">
        <div style="flex: 1;">
            <select name="producto_id" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; background: white;">
                <option value="">Todos los productos</option>
                @foreach($productos as $p)
                    <option value="{{ $p->id }}" {{ request('producto_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nombre }} (Stock: {{ $p->stock_actual }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="tipo" style="padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; background: white;">
                <option value="">Todos los tipos</option>
                <option value="entrada" {{ request('tipo') === 'entrada' ? 'selected' : '' }}>Entradas</option>
                <option value="salida" {{ request('tipo') === 'salida' ? 'selected' : '' }}>Salidas</option>
                <option value="ajuste" {{ request('tipo') === 'ajuste' ? 'selected' : '' }}>Ajustes</option>
            </select>
        </div>
        <button type="submit" class="btn-primary" style="padding: 0.6rem 1rem;">Filtrar</button>
    </form>

    <!-- Tabla de movimientos -->
    <table>
        <thead>
            <tr>
                <th>Fecha / Hora</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Stock Anterior</th>
                <th>Stock Posterior</th>
                <th>Motivo / Referencia</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movimientos as $m)
                <tr>
                    <td style="color: var(--text-muted); font-size: 0.82rem;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-weight: 700;">{{ $m->producto->nombre ?? 'N/A' }}</td>
                    <td>
                        @if($m->tipo === 'entrada')
                            <span style="background: #ECFDF5; color: #065F46; padding: 0.2rem 0.5rem; border-radius: 0.35rem; font-weight: 700; font-size: 0.75rem;">
                                ENTRADA
                            </span>
                        @elseif($m->tipo === 'salida')
                            <span style="background: #FEF2F2; color: #991B1B; padding: 0.2rem 0.5rem; border-radius: 0.35rem; font-weight: 700; font-size: 0.75rem;">
                                SALIDA
                            </span>
                        @else
                            <span style="background: #FFFBEB; color: #92400E; padding: 0.2rem 0.5rem; border-radius: 0.35rem; font-weight: 700; font-size: 0.75rem;">
                                AJUSTE
                            </span>
                        @endif
                    </td>
                    <td style="font-weight: 800; font-size: 0.95rem;">
                        {{ $m->tipo === 'entrada' ? '+' : '-' }}{{ $m->cantidad }}
                    </td>
                    <td>{{ $m->stock_anterior }}</td>
                    <td style="font-weight: 700;">{{ $m->stock_posterior }}</td>
                    <td>{{ $m->motivo }}</td>
                    <td style="font-size: 0.82rem; color: var(--text-muted);">{{ $m->usuario->name ?? 'Sistema' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $movimientos->links() }}
    </div>
</div>
@endsection
