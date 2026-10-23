@extends('layouts.tienda')

@section('titulo', 'Catálogo de Fórmulas y Farmacia Natural - FARMALUZ')

@section('contenido')
<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">

    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.25rem;">
        <div>
            <span style="color: var(--secondary); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.12em; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-leaf"></i> Botica & Laboratorio Galénico
            </span>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 800; margin: 0.25rem 0 0.5rem 0; color: #132A17;">
                Catálogo de Preparados & Medicina Natural
            </h1>
            <p style="color: var(--text-muted); margin: 0; font-size: 0.95rem;">
                Explora nuestras tinturas madre, ungüentos magistrales, aceites puros y fitoterapia especializada con formulación garantizada.
            </p>
        </div>
        <div style="display: flex; gap: 0.65rem; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('tienda.catalogo.pdf', ['ver' => 1] + request()->only(['categoria'])) }}" target="_blank" class="btn-outline" style="border-radius: 9999px; padding: 0.65rem 1.25rem; font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                <i class="fa-regular fa-file-pdf" style="color: var(--secondary); font-size: 1.1rem;"></i> Ver en PDF
            </a>
            <a href="{{ route('tienda.catalogo.pdf', request()->only(['categoria'])) }}" class="btn-primary" style="background: linear-gradient(135deg, #E4007C 0%, #BA0065 100%); border-radius: 9999px; padding: 0.65rem 1.35rem; font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; box-shadow: 0 4px 14px rgba(228, 0, 124, 0.35);">
                <i class="fa-solid fa-download" style="color: white !important;"></i> Descargar Catálogo PDF
            </a>
        </div>
    </div>

    <!-- Pestañas Rápidas de Categorías Destacadas -->
    <div style="display: flex; gap: 0.6rem; overflow-x: auto; padding-bottom: 1.25rem; margin-bottom: 1.5rem;">
        <a href="{{ route('tienda.catalogo') }}" style="text-decoration: none; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; white-space: nowrap; transition: all 0.2s; {{ !request('categoria') ? 'background: var(--primary); color: white;' : 'background: white; color: #475569; border: 1px solid var(--border-color);' }}">
            <i class="fa-solid fa-border-all"></i> Todas
        </a>
        @foreach($categorias as $c)
            <a href="{{ route('tienda.catalogo', ['categoria' => $c->slug]) }}" style="text-decoration: none; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; white-space: nowrap; transition: all 0.2s; {{ request('categoria') === $c->slug ? 'background: var(--primary); color: white;' : 'background: white; color: #475569; border: 1px solid var(--border-color);' }}">
                <i class="fa-solid fa-{{ $c->icono ?? 'tag' }}"></i> {{ $c->nombre }}
            </a>
        @endforeach
    </div>

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start;">
        
        <!-- SIDEBAR DE FILTROS AVANZADOS -->
        <aside class="card" style="padding: 1.5rem; border-radius: 0.85rem;">
            <form action="{{ route('tienda.catalogo') }}" method="GET">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <h3 style="font-size: 1.05rem; font-weight: 800; margin-top: 0; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.65rem; color: #132A17; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fa-solid fa-sliders" style="color: var(--primary);"></i> Filtros</span>
                    @if(request('categoria') || request('marca') || request('min_precio') || request('max_precio') || request('q'))
                        <a href="{{ route('tienda.catalogo') }}" style="font-size: 0.75rem; color: var(--secondary); text-decoration: none; font-weight: 600;">Limpiar todo</a>
                    @endif
                </h3>

                <!-- Búsqueda específica en catálogo -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #374151;">Buscar Producto</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="ej: Valeriana, Árnica..." style="width: 100%; box-sizing: border-box; padding: 0.6rem 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; font-size: 0.88rem; outline: none;">
                </div>

                <!-- Categorías -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #374151;">Rubro o Categoría</label>
                    <select name="categoria" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.5rem; background: #FFF; font-size: 0.88rem;">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->slug }}" {{ request('categoria') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Líneas / Laboratorios -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #374151;">Laboratorio / Línea</label>
                    <select name="marca" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.5rem; background: #FFF; font-size: 0.88rem;">
                        <option value="">Todos los laboratorios</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->slug }}" {{ request('marca') === $m->slug ? 'selected' : '' }}>
                                {{ $m->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rango de Precios en Bs. -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #374151;">Rango de Precio (Bs.)</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <input type="number" step="1" name="min_precio" value="{{ request('min_precio') }}" placeholder="Mínimo" style="width: 100%; box-sizing: border-box; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.4rem; font-size: 0.85rem;">
                        <input type="number" step="1" name="max_precio" value="{{ request('max_precio') }}" placeholder="Máximo" style="width: 100%; box-sizing: border-box; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.4rem; font-size: 0.85rem;">
                    </div>
                </div>

                <!-- Orden -->
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 0.4rem; color: #374151;">Ordenar Por</label>
                    <select name="orden" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.5rem; background: #FFF; font-size: 0.88rem;">
                        <option value="recientes" {{ request('orden') === 'recientes' ? 'selected' : '' }}>Más recientes</option>
                        <option value="precio_asc" {{ request('orden') === 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="precio_desc" {{ request('orden') === 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="nombre_asc" {{ request('orden') === 'nombre_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn-primary" style="flex: 1; justify-content: center; font-size: 0.9rem;">
                        <i class="fa-solid fa-filter"></i> Aplicar Filtros
                    </button>
                    <a href="{{ route('tienda.catalogo') }}" class="btn-outline" style="justify-content: center;" title="Limpiar"><i class="fa-solid fa-rotate-left"></i></a>
                </div>
            </form>
        </aside>

        <!-- GRID DE PRODUCTOS -->
        <div>
            @if($productos->isEmpty())
                <div class="card" style="padding: 3rem; text-align: center;">
                    <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #94A3B8; margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0 0 0.5rem 0;">No se encontraron productos</h3>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Intenta ajustar tus criterios de búsqueda o filtros.</p>
                    <a href="{{ route('tienda.catalogo') }}" class="btn-primary">Ver todo el catálogo</a>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem;">
                    @foreach($productos as $prod)
                        <div class="card" style="display: flex; flex-direction: column; border-radius: 0.75rem;">
                            <div style="background: white; height: 210px; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; border-bottom: 1px solid var(--border-color); overflow: hidden;">
                                @if($prod->imagen_principal)
                                    <img src="{{ $prod->imagen_principal }}" alt="{{ $prod->nombre }}"
                                        style="width: 100%; height: 100%; object-fit: contain; padding: 0.75rem; transition: transform 0.45s cubic-bezier(0.16,1,0.3,1);"
                                        onmouseover="this.style.transform='scale(1.07)'"
                                        onmouseout="this.style.transform='scale(1)'"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="display:none; width: 72px; height: 72px; border-radius: 50%; background: #FCE4EC; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(228,0,124,0.12);">
                                        <i class="fa-solid fa-mortar-pestle" style="font-size: 2.2rem; color: #E4007C;"></i>
                                    </div>
                                @else
                                    <div style="width: 72px; height: 72px; border-radius: 50%; background: #FCE4EC; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(228,0,124,0.12);">
                                        <i class="fa-solid fa-{{ $prod->categoria->icono ?? 'mortar-pestle' }}" style="font-size: 2.2rem; color: #E4007C;"></i>
                                    </div>
                                    <span style="font-size: 0.72rem; color: #52796F; font-weight: 600; margin-top: 0.6rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                        {{ $prod->marca->nombre ?? 'Farmaluz' }}
                                    </span>
                                @endif
                                @if($prod->tieneOfertaActiva())
                                    <span style="position: absolute; top: 0.75rem; right: 0.75rem; background: var(--secondary); color: white; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800; z-index: 2;">
                                        OFERTA
                                    </span>
                                @endif
                            </div>
                            <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
                                <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--primary); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.35rem;">
                                    <i class="fa-solid fa-leaf" style="font-size: 0.7rem;"></i> {{ $prod->categoria->nombre ?? 'Preparado' }}
                                </span>
                                <h3 style="font-size: 1rem; font-weight: 700; margin: 0 0 0.5rem 0; line-height: 1.35;">
                                    <a href="{{ route('tienda.producto', $prod->slug) }}" style="color: inherit; text-decoration: none;">
                                        {{ $prod->nombre }}
                                    </a>
                                </h3>
                                
                                <div style="display: flex; align-items: baseline; justify-content: space-between; margin-top: auto; margin-bottom: 1rem;">
                                    <div>
                                        @if($prod->tieneOfertaActiva())
                                            <span style="font-size: 1.25rem; font-weight: 800; color: #EF4444;">Bs. {{ number_format($prod->precio_oferta, 2) }}</span>
                                            <span style="font-size: 0.8rem; color: #94A3B8; text-decoration: line-through; margin-left: 0.3rem;">Bs. {{ number_format($prod->precio_venta, 2) }}</span>
                                        @else
                                            <span style="font-size: 1.25rem; font-weight: 800; color: #0F172A;">Bs. {{ number_format($prod->precio_venta, 2) }}</span>
                                        @endif
                                    </div>
                                    <span style="font-size: 0.75rem; color: #059669; font-weight: 600;">
                                        Stock: {{ $prod->stock_actual }}
                                    </span>
                                </div>

                                <form action="{{ route('carrito.agregar') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $prod->id }}">
                                    <input type="hidden" name="cantidad" value="1">
                                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; font-size: 0.88rem; padding: 0.5rem;">
                                        <i class="fa-solid fa-cart-plus"></i> Agregar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 2rem;">
                    {{ $productos->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
