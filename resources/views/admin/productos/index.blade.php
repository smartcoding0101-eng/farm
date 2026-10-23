@extends('layouts.admin')

@section('titulo', 'Gestión de Productos - NovaStore Admin')

@section('contenido')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.25rem 0; color: #132A17;">Gestión de Productos</h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.88rem;">Catálogo activo de preparados y farmacia natural con control de precios en Bolivianos y stock en tiempo real.</p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <!-- Botón Descargar Plantilla con Alerta de Confirmación -->
            <button type="button" onclick="confirmarDescargaExcel()" class="btn-outline" style="padding: 0.6rem 1rem; border-color: #2B7A38; color: #2B7A38; font-weight: 700; background: white; border-radius: 0.5rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-file-excel" style="color: #2B7A38;"></i> Descargar Plantilla Excel
            </button>

            <!-- Botón Carga Masiva -->
            <button type="button" onclick="abrirModalImportacion()" class="btn-primary" style="background: #E4007C; border-color: #E4007C; padding: 0.6rem 1rem; border-radius: 0.5rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-cloud-arrow-up"></i> Carga Masiva Excel
            </button>

            <!-- Botón Nuevo Producto Manual -->
            <a href="{{ route('admin.productos.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Buscador y filtro -->
    <form action="{{ route('admin.productos.index') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; background: #F8FAFC; padding: 1rem; border-radius: 0.5rem;">
        <div style="flex: 1;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o SKU..." style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; box-sizing: border-box;">
        </div>
        <div>
            <select name="categoria_id" style="padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.35rem; background: white;">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $c)
                    <option value="{{ $c->id }}" {{ request('categoria_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary" style="padding: 0.6rem 1.25rem;">Buscar</button>
    </form>

    <!-- Tabla de Productos -->
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>SKU</th>
                <th>Categoría</th>
                <th>Precio Venta</th>
                <th>Stock Actual</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $prod)
                <tr>
                    <td style="font-weight: 700;">
                        {{ $prod->nombre }}
                        @if($prod->destacado)
                            <span style="color: #D97706; font-size: 0.75rem;"><i class="fa-solid fa-star"></i> Destacado</span>
                        @endif
                    </td>
                    <td><code>{{ $prod->sku }}</code></td>
                    <td>{{ $prod->categoria->nombre ?? 'Sin categoría' }}</td>
                    <td style="font-weight: 800;">Bs. {{ number_format($prod->precio_venta, 2) }}</td>
                    <td>
                        @if($prod->stock_actual <= $prod->stock_minimo)
                            <span style="background: #FEF2F2; color: #991B1B; padding: 0.2rem 0.5rem; border-radius: 0.35rem; font-weight: 700; font-size: 0.78rem;">
                                {{ $prod->stock_actual }} (Bajo)
                            </span>
                        @else
                            <span style="color: #059669; font-weight: 700;">{{ $prod->stock_actual }}</span>
                        @endif
                    </td>
                    <td>
                        <span style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; background: #F1F5F9; padding: 0.2rem 0.5rem; border-radius: 0.35rem;">
                            {{ $prod->estado }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.productos.edit', $prod->id) }}" style="color: var(--primary); font-weight: 600; text-decoration: none; margin-right: 0.75rem;">
                            <i class="fa-solid fa-pen-to-square"></i> Editar
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $productos->links() }}
    </div>
</div>

<!-- MODAL 1: ALERTA DE CONFIRMACIÓN PARA DESCARGAR PLANTILLA EXCEL -->
<div id="modal-confirmar-descarga" style="display: none; position: fixed; inset: 0; background: rgba(14, 36, 19, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1.25rem;">
    <div class="card" style="max-width: 480px; width: 100%; padding: 2rem; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); text-align: center; border: 2px solid var(--border-color);">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: #E8F5E9; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem auto;">
            <i class="fa-solid fa-file-excel"></i>
        </div>
        
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.45rem; font-weight: 800; color: #132A17; margin: 0 0 0.5rem 0;">
            ¿Estás seguro de descargar la plantilla Excel?
        </h3>
        
        <p style="color: #475569; font-size: 0.92rem; line-height: 1.6; margin: 0 0 1.75rem 0;">
            Se descargará el archivo oficial <strong>.xlsx</strong> preconfigurado con las columnas oficiales, ejemplos reales de farmacia natural y la lista de categorías y marcas válidas.
        </p>

        <div style="display: flex; gap: 0.75rem; justify-content: center;">
            <button type="button" onclick="cerrarModalDescarga()" style="padding: 0.65rem 1.5rem; border: 1.5px solid #CBD5E1; background: white; color: #475569; font-weight: 700; border-radius: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                Cancelar
            </button>
            <a id="btn-ejecutar-descarga" href="{{ route('admin.productos.plantilla') }}" onclick="cerrarModalDescarga()" class="btn-primary" style="padding: 0.65rem 1.5rem; font-size: 0.9rem; border-radius: 0.5rem;">
                <i class="fa-solid fa-download"></i> Sí, Descargar Excel
            </a>
        </div>
    </div>
</div>

<!-- MODAL 2: CARGA MASIVA DE PRODUCTOS (EXCEL + ZIP) -->
<div id="modal-importacion" style="display: none; position: fixed; inset: 0; background: rgba(14, 36, 19, 0.65); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1.25rem;">
    <div class="card" style="max-width: 580px; width: 100%; padding: 2rem; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 2px solid var(--border-color);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: #FCE4EC; color: #E4007C; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div>
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 800; color: #132A17; margin: 0;">Carga Masiva de Productos</h3>
                    <span style="font-size: 0.78rem; color: #64748B;">Farmaluz Preparados Magistrales</span>
                </div>
            </div>
            <button type="button" onclick="cerrarModalImportacion()" style="background: none; border: none; font-size: 1.25rem; color: #94A3B8; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.productos.importar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Archivo Excel -->
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-weight: 700; font-size: 0.88rem; color: #374151; margin-bottom: 0.35rem;">
                    1. Archivo Excel (.xlsx, .xls) *
                </label>
                <input type="file" name="archivo_excel" accept=".xlsx,.xls,.csv" required style="width: 100%; box-sizing: border-box; padding: 0.65rem; border: 1.5px dashed var(--primary); border-radius: 0.5rem; background: #F9FBF9; font-size: 0.88rem; cursor: pointer;">
                <span style="display: block; font-size: 0.76rem; color: #64748B; margin-top: 0.25rem;">
                    Si no tienes el formato, <a href="javascript:void(0)" onclick="confirmarDescargaExcel()" style="color: var(--primary); font-weight: 700;">descarga la plantilla aquí</a>.
                </span>
            </div>

            <!-- Archivo ZIP de Imágenes (Opcional) -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 700; font-size: 0.88rem; color: #374151; margin-bottom: 0.35rem;">
                    2. Archivo Comprimido ZIP de Imágenes (Opcional)
                </label>
                <input type="file" name="archivo_zip" accept=".zip" style="width: 100%; box-sizing: border-box; padding: 0.65rem; border: 1.5px dashed #CBD5E1; border-radius: 0.5rem; background: #F8FAFC; font-size: 0.88rem; cursor: pointer;">
                <span style="display: block; font-size: 0.76rem; color: #64748B; margin-top: 0.25rem;">
                    Contiene las fotos nombradas en el Excel (ej: <code>unguento_arnica.jpg</code>). También se aceptan URLs web directas en la columna de imagen.
                </span>
            </div>

            <!-- Caja Informativa de Código Automático y Ajuste -->
            <div style="background: #E8F5E9; border: 1px solid #C8E6C9; border-radius: 0.5rem; padding: 0.85rem; margin-bottom: 1.5rem; font-size: 0.82rem; color: #1E5C29; line-height: 1.5;">
                <i class="fa-solid fa-circle-check" style="color: var(--primary);"></i> <strong>Asignación Automática:</strong> Si dejas el SKU vacío, se creará automáticamente según la categoría (ej: <code>FIT-0001</code>, <code>MAG-0001</code>, <code>DER-0001</code>). Las imágenes se auto-ajustarán y optimizarán al almacenamiento seguro.
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="cerrarModalImportacion()" style="padding: 0.65rem 1.25rem; border: 1.5px solid #CBD5E1; background: white; color: #475569; font-weight: 700; border-radius: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary" style="background: #E4007C; border-color: #E4007C; padding: 0.65rem 1.5rem; font-size: 0.9rem;">
                    <i class="fa-solid fa-upload"></i> Iniciar Importación
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmarDescargaExcel() {
        const modal = document.getElementById('modal-confirmar-descarga');
        modal.style.display = 'flex';
    }

    function cerrarModalDescarga() {
        const modal = document.getElementById('modal-confirmar-descarga');
        modal.style.display = 'none';
    }

    function abrirModalImportacion() {
        const modal = document.getElementById('modal-importacion');
        modal.style.display = 'flex';
    }

    function cerrarModalImportacion() {
        const modal = document.getElementById('modal-importacion');
        modal.style.display = 'none';
    }

    // Cerrar al hacer clic fuera del modal
    window.addEventListener('click', function(e) {
        const modalDescarga = document.getElementById('modal-confirmar-descarga');
        const modalImport = document.getElementById('modal-importacion');
        if (e.target === modalDescarga) cerrarModalDescarga();
        if (e.target === modalImport) cerrarModalImportacion();
    });
</script>
@endsection
