@extends('layouts.admin')

@section('titulo', 'Registrar Nuevo Producto - NovaStore Admin')

@section('contenido')
<div class="card" style="max-width: 900px;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.productos.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.88rem; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Volver a productos
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0.5rem 0 0 0;">Nuevo Producto para el Catálogo</h1>
    </div>

    <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            
            <!-- Columna Principal -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Nombre del Producto *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Laptop ASUS ZenBook 14 OLED" style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Categoría *</label>
                        <select name="categoria_id" required style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.4rem; background: white;">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}" {{ old('categoria_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Marca</label>
                        <select name="marca_id" style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.4rem; background: white;">
                            <option value="">Sin marca específica</option>
                            @foreach($marcas as $m)
                                <option value="{{ $m->id }}" {{ old('marca_id') == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Descripción Breve</label>
                    <textarea name="descripcion_corta" rows="3" placeholder="Resumen clave para el catálogo..." style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">{{ old('descripcion_corta') }}</textarea>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Descripción Completa / Ficha Técnica</label>
                    <textarea name="descripcion_completa" rows="6" placeholder="Detalles técnicos, garantía y especificaciones..." style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">{{ old('descripcion_completa') }}</textarea>
                </div>
            </div>

            <!-- Columna Lateral: Precios, Stock e Imagen -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1.25rem;">
                    <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; font-weight: 700;">Precios (Bs.)</h4>
                    
                    <div style="margin-bottom: 0.85rem;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Precio de Venta Oficial *</label>
                        <input type="number" step="0.01" name="precio_venta" value="{{ old('precio_venta') }}" required placeholder="0.00" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; font-weight: 700; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 0.85rem;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Costo de Adquisición * (Confidencial)</label>
                        <input type="number" step="0.01" name="costo_adquisicion" value="{{ old('costo_adquisicion', '0.00') }}" required placeholder="0.00" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Precio de Oferta (Opcional)</label>
                        <input type="number" step="0.01" name="precio_oferta" value="{{ old('precio_oferta') }}" placeholder="0.00" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
                    </div>
                </div>

                <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1.25rem;">
                    <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; font-weight: 700;">Inventario Inicial</h4>

                    <div style="margin-bottom: 0.85rem;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">SKU Único *</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="Ej: LAP-ASUS-14" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; text-transform: uppercase; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 0.85rem;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Stock Inicial *</label>
                        <input type="number" name="stock_inicial" value="{{ old('stock_inicial', 10) }}" min="0" required style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Alerta Stock Mínimo *</label>
                        <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 3) }}" min="1" required style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Fotografía Principal</label>
                    <input type="file" name="imagen" accept="image/*" style="font-size: 0.85rem; width: 100%;">
                </div>

                <div>
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; cursor: pointer;">
                        <input type="checkbox" name="destacado" value="1" {{ old('destacado') ? 'checked' : '' }}>
                        <span>Mostrar en "Productos Destacados"</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem;">
                    <i class="fa-solid fa-save"></i> Guardar Producto
                </button>

            </div>

        </div>
    </form>
</div>
@endsection
