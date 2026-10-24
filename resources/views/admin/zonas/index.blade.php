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

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Zona / Cobertura</th>
                    <th>Departamento</th>
                    <th>Costo de Envío</th>
                    <th>Envío Gratis Desde</th>
                    <th>Tiempo Estimado</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zonas as $z)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.65rem;">
                                <div style="width: 34px; height: 34px; border-radius: var(--radius-sm); background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </div>
                                <span style="font-weight: 700; color: #0F2414; font-size: 0.92rem;">{{ $z->nombre }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-neutral"><i class="fa-solid fa-location-dot"></i> {{ $z->departamento }}</span>
                        </td>
                        <td>
                            <span style="font-weight: 900; color: #0F172A; font-size: 0.95rem;">Bs. {{ number_format($z->costo_envio, 2) }}</span>
                        </td>
                        <td>
                            @if($z->monto_minimo_envio_gratis)
                                <span class="badge badge-success"><i class="fa-solid fa-gift"></i> Desde Bs. {{ number_format($z->monto_minimo_envio_gratis, 2) }}</span>
                            @else
                                <span style="color: #94A3B8; font-size: 0.8rem;">Sin envío gratis</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size: 0.84rem; color: #475569;"><i class="fa-solid fa-clock" style="color: #94A3B8;"></i> {{ $z->tiempo_estimado }}</span>
                        </td>
                        <td>
                            @if($z->activo)
                                <span class="badge badge-success"><i class="fa-solid fa-check"></i> Activa</span>
                            @else
                                <span class="badge badge-danger">Inactiva</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.zonas.edit', $z->id) }}" class="btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            No hay zonas de envío configuradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $zonas->links() }}
    </div>
</div>
@endsection
