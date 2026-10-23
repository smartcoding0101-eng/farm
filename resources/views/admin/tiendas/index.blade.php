@extends('layouts.admin')

@section('titulo', 'Mis Tiendas - NovaStore Admin')

@section('contenido')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0; color: #0F172A;">Mis Tiendas</h1>
        <p style="margin: 0.25rem 0 0; color: #64748B; font-size: 0.9rem;">Gestiona y cambia entre las diferentes tiendas y sucursales asignadas a tu cuenta.</p>
    </div>
    <a href="{{ route('admin.tiendas.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Crear Nueva Tienda
    </a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
    @php
        $tiendaActiva = app(\App\Services\TenantManager::class)->getTienda();
    @endphp

    @forelse($tiendas as $tienda)
        <div class="card" style="border: 2px solid {{ $tiendaActiva && $tiendaActiva->id == $tienda->id ? '#2563EB' : '#E2E8F0' }}; position: relative; display: flex; flex-direction: column; justify-content: space-between;">
            @if($tiendaActiva && $tiendaActiva->id == $tienda->id)
                <span style="position: absolute; top: 1rem; right: 1rem; background: #2563EB; color: white; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.6rem; border-radius: 9999px; text-transform: uppercase;">
                    <i class="fa-solid fa-check"></i> Activa
                </span>
            @endif

            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 0.5rem; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #0F172A;">{{ $tienda->nombre }}</h3>
                        <span style="font-size: 0.8rem; color: #64748B;">Slug: {{ $tienda->slug }}</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 1.25rem; background: #F8FAFC; padding: 0.75rem; border-radius: 0.5rem;">
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block;">Productos</span>
                        <strong style="font-size: 1.1rem; color: #0F172A;">{{ $tienda->productos_count }}</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.75rem; color: #64748B; display: block;">Pedidos</span>
                        <strong style="font-size: 1.1rem; color: #0F172A;">{{ $tienda->pedidos_count }}</strong>
                    </div>
                </div>

                <div style="font-size: 0.85rem; color: #475569; margin-bottom: 1.5rem;">
                    <p style="margin: 0.25rem 0;"><i class="fa-solid fa-coins" style="width: 18px; color: #94A3B8;"></i> Moneda: <strong>{{ $tienda->moneda }}</strong></p>
                    <p style="margin: 0.25rem 0;"><i class="fa-solid fa-shield-halved" style="width: 18px; color: #94A3B8;"></i> Rol: <strong>{{ ucfirst($tienda->pivot->rol ?? 'Dueño') }}</strong></p>
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
