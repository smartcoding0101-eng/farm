@extends('layouts.admin')

@section('titulo', 'Crear Nueva Tienda - NovaStore Admin')

@section('contenido')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.tiendas.index') }}" style="color: #64748B; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
            <i class="fa-solid fa-arrow-left"></i> Volver a Mis Tiendas
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0; color: #0F172A;">Crear Nueva Tienda</h1>
        <p style="margin: 0.25rem 0 0; color: #64748B; font-size: 0.9rem;">Registra una nueva sucursal o comercio independiente con su propio catálogo y pedidos.</p>
    </div>

    <div class="card">
        <form action="{{ route('admin.tiendas.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label for="nombre" style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Nombre de la Tienda *</label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required placeholder="Ej: NovaStore Sucursal Calacoto" style="width: 100%; box-sizing: border-box; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 0.5rem; font-size: 0.9rem; outline: none;">
                @error('nombre')
                    <span style="color: #EF4444; font-size: 0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="slug" style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Slug / Identificador URL (opcional)</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="ej: novastore-calacoto (se genera automático si queda vacío)" style="width: 100%; box-sizing: border-box; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 0.5rem; font-size: 0.9rem; outline: none;">
                @error('slug')
                    <span style="color: #EF4444; font-size: 0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label for="telefono" style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Teléfono / WhatsApp</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" placeholder="+591 70000000" style="width: 100%; box-sizing: border-box; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 0.5rem; font-size: 0.9rem; outline: none;">
                </div>
                <div>
                    <label for="moneda" style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Moneda Principal *</label>
                    <select name="moneda" id="moneda" required style="width: 100%; box-sizing: border-box; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 0.5rem; font-size: 0.9rem; outline: none; background: white;">
                        <option value="BOB" {{ old('moneda') == 'BOB' ? 'selected' : '' }}>BOB (Bolivianos)</option>
                        <option value="USD" {{ old('moneda') == 'USD' ? 'selected' : '' }}>USD (Dólares)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label for="email" style="display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Email de Contacto</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="contacto@tienda.com" style="width: 100%; box-sizing: border-box; padding: 0.65rem 0.85rem; border: 1px solid #CBD5E1; border-radius: 0.5rem; font-size: 0.9rem; outline: none;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <a href="{{ route('admin.tiendas.index') }}" style="padding: 0.65rem 1.25rem; border-radius: 0.5rem; border: 1px solid #CBD5E1; color: #475569; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Guardar y Activar Tienda
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
