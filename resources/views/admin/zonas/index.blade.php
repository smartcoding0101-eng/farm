@extends('layouts.admin')

@section('titulo', 'Zonas de Envío y Tarifas - NovaStore Admin')

@section('contenido')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.25rem 0;">Zonas de Cobertura y Tarifas de Envío</h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.88rem;">Configuración de costos de flete, tiempos estimados y umbral para envío gratuito en Bolivia.</p>
        </div>
        <a href="{{ route('admin.zonas.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Nueva Zona
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Zona / Cobertura</th>
                <th>Departamento</th>
                <th>Costo de Envío</th>
                <th>Envío Gratis Desde</th>
                <th>Tiempo Estimado</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($zonas as $z)
                <tr>
                    <td style="font-weight: 700;">{{ $z->nombre }}</td>
                    <td>{{ $z->departamento }}</td>
                    <td style="font-weight: 800; color: #0F172A;">Bs. {{ number_format($z->costo_envio, 2) }}</td>
                    <td>
                        @if($z->monto_minimo_envio_gratis)
                            <span style="color: #059669; font-weight: 700;">Bs. {{ number_format($z->monto_minimo_envio_gratis, 2) }}</span>
                        @else
                            <span style="color: #94A3B8;">Sin envío gratis</span>
                        @endif
                    </td>
                    <td>{{ $z->tiempo_estimado }}</td>
                    <td>
                        @if($z->activo)
                            <span style="color: #059669; font-weight: 600; font-size: 0.8rem;"><i class="fa-solid fa-check"></i> Activa</span>
                        @else
                            <span style="color: #EF4444; font-weight: 600; font-size: 0.8rem;">Inactiva</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.zonas.edit', $z->id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.85rem;">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $zonas->links() }}
    </div>
</div>
@endsection
