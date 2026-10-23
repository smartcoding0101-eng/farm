@extends('layouts.admin')

@section('titulo', 'Editar Producto - NovaStore Admin')

@section('contenido')
<div class="card" style="max-width: 900px;">
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <a href="{{ route('admin.productos.index') }}" style="color: var(--primary); text-decoration: none; font-size: 0.88rem; font-weight: 600;">
                <i class="fa-solid fa-arrow-left"></i> Volver a productos
            </a>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0.5rem 0 0 0;">Editar: {{ $producto->nombre }}</h1>
        </div>
        <a href="{{ route('tienda.producto', $producto->slug) }}" target="_blank" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver en tienda pública
        </a>
    </div>

    <!-- Pestañas o secciones: Datos y Ajuste de Stock -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        
        <!-- Formulario de Actualización de Datos -->
        <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Nombre del Producto *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Categoría *</label>
                        <select name="categoria_id" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; background: white;">
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}" {{ old('categoria_id', $producto->categoria_id) == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Marca</label>
                        <select name="marca_id" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; background: white;">
                            <option value="">Sin marca</option>
                            @foreach($marcas as $m)
                                <option value="{{ $m->id }}" {{ old('marca_id', $producto->marca_id) == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">SKU *</label>
                        <input type="text" name="sku" value="{{ old('sku', $producto->sku) }}" required style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Estado de Publicación</label>
                        <select name="estado" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; background: white;">
                            <option value="publicado" {{ $producto->estado === 'publicado' ? 'selected' : '' }}>Publicado</option>
                            <option value="borrador" {{ $producto->estado === 'borrador' ? 'selected' : '' }}>Borrador (Oculto)</option>
                            <option value="archivado" {{ $producto->estado === 'archivado' ? 'selected' : '' }}>Archivado</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.82rem; margin-bottom: 0.25rem;">Precio Venta (Bs.) *</label>
                        <input type="number" step="0.01" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}" required style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; font-weight: 700; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.82rem; margin-bottom: 0.25rem;">Costo Adquisición (Bs.) *</label>
                        <input type="number" step="0.01" name="costo_adquisicion" value="{{ old('costo_adquisicion', $producto->costo_adquisicion) }}" required style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.82rem; margin-bottom: 0.25rem;">Precio Oferta (Bs.)</label>
                        <input type="number" step="0.01" name="precio_oferta" value="{{ old('precio_oferta', $producto->precio_oferta) }}" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Descripción Breve</label>
                    <textarea name="descripcion_corta" rows="2" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">{{ old('descripcion_corta', $producto->descripcion_corta) }}</textarea>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.35rem;">Descripción Completa</label>
                    <textarea name="descripcion_completa" rows="5" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">{{ old('descripcion_completa', $producto->descripcion_completa) }}</textarea>
                </div>

                <div>
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; cursor: pointer;">
                        <input type="checkbox" name="destacado" value="1" {{ $producto->destacado ? 'checked' : '' }}>
                        <span>Producto Destacado en Portada</span>
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem;">
                        <i class="fa-solid fa-floppy-disk"></i> Actualizar Producto
                    </button>
                </div>
            </div>
        </form>

        <!-- Columna Lateral: Ajuste Rápido de Kardex / Inventario -->
        <div>
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.1rem; font-weight: 800;">Control de Existencias</h3>
                <div style="font-size: 2.25rem; font-weight: 900; color: #0F172A; margin-bottom: 0.5rem;">
                    {{ $producto->stock_actual }} <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">unidades</span>
                </div>
                <div style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                    Alerta de stock mínimo: <strong>{{ $producto->stock_minimo }}</strong>
                </div>

                <h4 style="margin: 0 0 0.75rem 0; font-size: 0.95rem; font-weight: 700; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                    Registrar Movimiento en Kardex
                </h4>

                <form action="{{ route('admin.productos.stock', $producto->id) }}" method="POST">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Tipo de Movimiento</label>
                            <select name="tipo" style="width: 100%; padding: 0.55rem; border: 1px solid var(--border-color); border-radius: 0.35rem; background: white;">
                                <option value="entrada">Entrada (Compra / Reposición)</option>
                                <option value="salida">Salida (Merma / Daño)</option>
                                <option value="ajuste">Ajuste Directo</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Cantidad</label>
                            <input type="number" name="cantidad" min="1" required placeholder="Ej: 5" style="width: 100%; padding: 0.55rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.25rem;">Motivo / Justificación *</label>
                            <input type="text" name="motivo" required placeholder="Ej: Ingreso por factura 4590" style="width: 100%; padding: 0.55rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
                        </div>

                        <button type="submit" class="btn-primary" style="justify-content: center; width: 100%; padding: 0.65rem;">
                            <i class="fa-solid fa-boxes-stacked"></i> Aplicar en Kardex
                        </button>
                    </div>
                </form>
            </div>

            <!-- Enlace al historial de kardex de este producto -->
            <a href="{{ route('admin.reportes.movimientos', ['producto_id' => $producto->id]) }}" class="btn-outline" style="width: 100%; justify-content: center; box-sizing: border-box;">
                <i class="fa-solid fa-list-check"></i> Ver Kardex de este Producto
            </a>
        </div>

    </div>
</div>
@endsection
