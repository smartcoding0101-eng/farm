@extends('layouts.admin')

@section('titulo', 'Nueva Categoría - NovaStore Admin')

@section('contenido')
<div class="card" style="max-width: 650px;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.categorias.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.88rem; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Volver a categorías
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0.5rem 0 0 0;">Nueva Categoría Multirrubro</h1>
    </div>

    <form action="{{ route('admin.categorias.store') }}" method="POST">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Nombre de la Categoría *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Herramientas Eléctricas" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Categoría Superior / Padre (Opcional)</label>
                <select name="categoria_padre_id" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; background: white;">
                    <option value="">Ninguna (Categoría Principal)</option>
                    @foreach($categoriasPadre as $p)
                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Descripción</label>
                <textarea name="descripcion" rows="3" placeholder="Breve descripción del departamento..." style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">{{ old('descripcion') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Icono FontAwesome (ej: tag, laptop)</label>
                    <input type="text" name="icono" value="{{ old('icono', 'tag') }}" placeholder="tag" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Orden de Visualización</label>
                    <input type="number" name="orden" value="{{ old('orden', 0) }}" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>
            </div>

            <div style="display: flex; gap: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; cursor: pointer;">
                    <input type="checkbox" name="destacada" value="1" {{ old('destacada') ? 'checked' : '' }}>
                    <span>Destacada en Home</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; cursor: pointer;">
                    <input type="checkbox" name="activo" value="1" checked>
                    <span>Categoría Activa</span>
                </label>
            </div>

            <button type="submit" class="btn-primary" style="justify-content: center; padding: 0.85rem; margin-top: 0.5rem;">
                <i class="fa-solid fa-save"></i> Guardar Categoría
            </button>
        </div>
    </form>
</div>
@endsection
