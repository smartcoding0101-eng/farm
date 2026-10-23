@extends('layouts.admin')

@section('titulo', 'Nueva Zona de Envío - NovaStore Admin')

@section('contenido')
<div class="card" style="max-width: 650px;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.zonas.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.88rem; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Volver a zonas
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0.5rem 0 0 0;">Nueva Zona de Envío</h1>
    </div>

    <form action="{{ route('admin.zonas.store') }}" method="POST">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Nombre de la Zona / Cobertura *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Cochabamba Urbano (Cercado)" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Departamento / Región *</label>
                    <input type="text" name="departamento" value="{{ old('departamento', 'La Paz') }}" required placeholder="Ej: Cochabamba" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Tiempo Estimado de Entrega *</label>
                    <input type="text" name="tiempo_estimado" value="{{ old('tiempo_estimado', '24 a 48 horas') }}" required placeholder="Ej: 24 horas" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Costo de Envío (Bs.) *</label>
                    <input type="number" step="0.01" name="costo_envio" value="{{ old('costo_envio', '15.00') }}" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; font-weight: 700; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Monto Mínimo Envío Gratis (Bs.)</label>
                    <input type="number" step="0.01" name="monto_minimo_envio_gratis" value="{{ old('monto_minimo_envio_gratis') }}" placeholder="Opcional" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>
            </div>

            <div>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; cursor: pointer;">
                    <input type="checkbox" name="activo" value="1" checked>
                    <span>Zona Activa para Compras en Checkout</span>
                </label>
            </div>

            <button type="submit" class="btn-primary" style="justify-content: center; padding: 0.85rem; margin-top: 0.5rem;">
                <i class="fa-solid fa-save"></i> Guardar Zona de Envío
            </button>
        </div>
    </form>
</div>
@endsection
