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

    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Slug</th>
                <th>Categoría Padre</th>
                <th>Total Productos</th>
                <th>Orden</th>
                <th>Destacada</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categorias as $c)
                <tr>
                    <td style="font-weight: 700;">{{ $c->nombre }}</td>
                    <td><code>{{ $c->slug }}</code></td>
                    <td>{{ $c->padre->nombre ?? 'Principal' }}</td>
                    <td><span style="font-weight: 700; color: var(--primary);">{{ $c->productos_count }}</span></td>
                    <td>{{ $c->orden }}</td>
                    <td>
                        @if($c->destacada)
                            <span style="color: #D97706; font-size: 0.8rem; font-weight: 600;"><i class="fa-solid fa-star"></i> Sí</span>
                        @else
                            <span style="color: #94A3B8; font-size: 0.8rem;">No</span>
                        @endif
                    </td>
                    <td>
                        @if($c->activo)
                            <span style="color: #059669; font-weight: 600; font-size: 0.8rem;"><i class="fa-solid fa-check"></i> Activa</span>
                        @else
                            <span style="color: #EF4444; font-weight: 600; font-size: 0.8rem;">Inactiva</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.categorias.edit', $c->id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.85rem;">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $categorias->links() }}
    </div>
</div>
@endsection
