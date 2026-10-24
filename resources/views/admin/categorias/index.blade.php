@extends('layouts.admin')

@section('titulo', 'Gestión de Categorías - NovaStore Admin')

@section('contenido')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.25rem 0;">Rubros y Categorías</h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.88rem;">Estructura multirrubro para organizar el catálogo de la tienda.</p>
        </div>
        <a href="{{ route('admin.categorias.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Nueva Categoría
        </a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Categoría / Rubro</th>
                    <th>Slug</th>
                    <th>Jerarquía</th>
                    <th>Productos</th>
                    <th>Orden</th>
                    <th>Destacada</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $c)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.65rem;">
                                <div style="width: 34px; height: 34px; border-radius: var(--radius-sm); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                    <i class="fa-solid fa-{{ $c->icono ?? 'tag' }}"></i>
                                </div>
                                <div>
                                    <span style="font-weight: 700; color: #0F2414; font-size: 0.92rem; display: block;">{{ $c->nombre }}</span>
                                    @if($c->descripcion)
                                        <span style="font-size: 0.76rem; color: var(--text-muted);">{{ Str::limit($c->descripcion, 40) }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><code style="background: #F1F5F9; padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 0.78rem;">{{ $c->slug }}</code></td>
                        <td>
                            @if($c->padre)
                                <span class="badge badge-neutral"><i class="fa-solid fa-arrow-turn-up fa-rotate-90"></i> {{ $c->padre->nombre }}</span>
                            @else
                                <span class="badge badge-info"><i class="fa-solid fa-folder"></i> Principal</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-neutral" style="font-weight: 800; font-size: 0.82rem;">
                                {{ $c->productos_count }} ítems
                            </span>
                        </td>
                        <td><span style="font-weight: 700; color: #64748B;">{{ $c->orden }}</span></td>
                        <td>
                            @if($c->destacada)
                                <span class="badge badge-warning"><i class="fa-solid fa-star"></i> Destacada</span>
                            @else
                                <span style="color: #94A3B8; font-size: 0.8rem;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($c->activo)
                                <span class="badge badge-success"><i class="fa-solid fa-check"></i> Activa</span>
                            @else
                                <span class="badge badge-danger">Inactiva</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.categorias.edit', $c->id) }}" class="btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            No existen categorías registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $categorias->links() }}
    </div>
</div>
@endsection
