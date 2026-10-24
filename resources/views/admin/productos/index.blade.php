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

    <!-- Tabla de Productos Responsive -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">Img</th>
                    <th>Producto & Fórmula</th>
                    <th>SKU</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Stock Actual</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $prod)
                    <tr>
                        <td>
                            <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); overflow: hidden; background: #F1F5F9; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center;">
                                @if($prod->imagen_principal)
                                    <img src="{{ $prod->imagen_principal }}" alt="{{ $prod->nombre }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='/images/farmaluz-logo.png'">
                                @else
                                    <i class="fa-solid fa-mortar-pestle" style="color: #94A3B8; font-size: 1.1rem;"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <a href="{{ route('admin.productos.edit', $prod->id) }}" style="font-weight: 700; color: #0F2414; text-decoration: none; font-size: 0.92rem; display: block; line-height: 1.3;">
                                    {{ $prod->nombre }}
                                </a>
                                <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.25rem;">
                                    @if($prod->destacado)
                                        <span class="badge badge-warning" style="font-size: 0.68rem; padding: 0.15rem 0.45rem;">
                                            <i class="fa-solid fa-star"></i> Destacado
                                        </span>
                                    @endif
                                    @if($prod->nuevo)
                                        <span class="badge badge-info" style="font-size: 0.68rem; padding: 0.15rem 0.45rem;">
                                            Nuevo
                                        </span>
                                    @endif
                                    @if($prod->precio_oferta)
                                        <span class="badge badge-danger" style="font-size: 0.68rem; padding: 0.15rem 0.45rem;">
                                            Oferta Bs. {{ number_format($prod->precio_oferta, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <code style="background: #F1F5F9; padding: 0.2rem 0.45rem; border-radius: 4px; font-size: 0.78rem; font-weight: 600; color: #475569;">{{ $prod->sku }}</code>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: #334155; font-size: 0.84rem;">
                                {{ $prod->categoria->nombre ?? 'Sin rubro' }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 900; color: #0F172A; font-size: 0.95rem;">
                                Bs. {{ number_format($prod->precio_venta, 2) }}
                            </div>
                        </td>
                        <td>
                            @if($prod->stock_actual <= $prod->stock_minimo)
                                <span class="badge badge-danger">
                                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $prod->stock_actual }} (Bajo)
                                </span>
                            @else
                                <span class="badge badge-success">
                                    <i class="fa-solid fa-circle-check"></i> {{ $prod->stock_actual }} u.
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($prod->estado === 'publicado')
                                <span class="badge badge-success">Publicado</span>
                            @elseif($prod->estado === 'borrador')
                                <span class="badge badge-warning">Borrador</span>
                            @else
                                <span class="badge badge-neutral">Archivado</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('admin.productos.edit', $prod->id) }}" class="btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <i class="fa-solid fa-box-open" style="font-size: 2.5rem; color: #CBD5E1; margin-bottom: 0.75rem; display: block;"></i>
                            No se encontraron productos coincidentes con los filtros.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

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
