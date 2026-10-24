@extends('layouts.admin')

@section('titulo', 'Mis Tiendas - NovaStore Admin')

@section('contenido')
<div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0; color: #0F2414;">Mis Tiendas & Sucursales</h1>
        <p style="margin: 0.35rem 0 0; color: var(--text-muted); font-size: 0.92rem;">Gestión multi-tenant para administrar inventarios, pedidos y sucursales farmacéuticas independientes.</p>
    </div>
    <a href="{{ route('admin.tiendas.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Crear Nueva Tienda
    </a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.75rem;">
    @php
        $tiendaActiva = app(\App\Services\TenantManager::class)->getTienda();
    @endphp

    @forelse($tiendas as $tienda)
        <div class="card" style="border: 2px solid {{ $tiendaActiva && $tiendaActiva->id == $tienda->id ? 'var(--primary)' : 'var(--border-color)' }}; position: relative; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;">
            @if($tiendaActiva && $tiendaActiva->id == $tienda->id)
                <span class="badge badge-success" style="position: absolute; top: 1.25rem; right: 1.25rem; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.2);">
                    <i class="fa-solid fa-circle-check"></i> TIENDA ACTIVA
                </span>
            @endif

            <div>
                <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.25rem;">
                    <div style="width: 52px; height: 52px; border-radius: var(--radius-sm); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #0F2414;">{{ $tienda->nombre }}</h2>
                        <span style="font-size: 0.78rem; color: var(--text-muted); font-family: monospace;">slug: {{ $tienda->slug }}</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.5rem; background: #F8FAF8; padding: 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    <div>
                        <span style="font-size: 0.74rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block;">Catálogo</span>
                        <strong style="font-size: 1.3rem; font-weight: 900; color: #0F2414;">{{ $tienda->productos_count }}</strong>
                        <span style="font-size: 0.75rem; color: #64748B;"> productos</span>
                    </div>
                    <div>
                        <span style="font-size: 0.74rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block;">Ventas</span>
                        <strong style="font-size: 1.3rem; font-weight: 900; color: #0F2414;">{{ $tienda->pedidos_count }}</strong>
                        <span style="font-size: 0.75rem; color: #64748B;"> pedidos</span>
                    </div>
                </div>

                <div style="font-size: 0.86rem; color: #475569; margin-bottom: 1.5rem;">
                    <p style="margin: 0.35rem 0;"><i class="fa-solid fa-coins" style="width: 20px; color: #94A3B8;"></i> Moneda: <strong>{{ $tienda->moneda }} (Bolivianos)</strong></p>
                    <p style="margin: 0.35rem 0;"><i class="fa-solid fa-shield-halved" style="width: 20px; color: #94A3B8;"></i> Permiso: <strong>{{ ucfirst($tienda->pivot->rol ?? 'Dueño') }}</strong></p>
                </div>
            </div>


            <div>
                @if(!$tiendaActiva || $tiendaActiva->id != $tienda->id)
                    <form action="{{ route('admin.tiendas.switch') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tienda_id" value="{{ $tienda->id }}">
                        <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                            <i class="fa-solid fa-arrow-right-arrow-left"></i> Cambiar a esta Tienda
                        </button>
                    </form>
                @else
                    <button disabled style="width: 100%; justify-content: center; background: #E2E8F0; color: #64748B; border: none; padding: 0.6rem; border-radius: 0.5rem; font-weight: 600; cursor: not-allowed; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-circle-check"></i> Tienda actualmente en uso
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
            <i class="fa-solid fa-store-slash" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem;"></i>
            <h3 style="margin: 0; color: #0F172A;">No tienes tiendas asignadas</h3>
            <p style="color: #64748B;">Crea tu primera tienda para comenzar a registrar catálogo y pedidos.</p>
        </div>
    @endforelse
</div>
@endsection
