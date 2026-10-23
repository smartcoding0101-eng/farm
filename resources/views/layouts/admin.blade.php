<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Panel Administrativo - FARMALUZ')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #F4F7F4;
            --primary: #2B7A38;
            --primary-dark: #1E5C29;
            --secondary: #E4007C;
            --sidebar-bg: #0D2313;
            --card-bg: #FFFFFF;
            --text-main: #132A17;
            --text-muted: #52796F;
            --border-color: #E0EBE0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: #94A3B8;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
        }
        .sidebar-brand {
            padding: 1.5rem;
            color: white;
            font-size: 1.25rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid #1E293B;
        }
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
            flex: 1;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #94A3B8;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: white;
            background: #1E293B;
            border-left: 3px solid var(--primary);
        }
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .admin-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-body {
            padding: 2rem;
            flex: 1;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
        }
        .btn-success { background: #10B981; color: white; padding: 0.4rem 0.8rem; border-radius: 0.35rem; border: none; cursor: pointer; font-weight: 600; }
        .btn-danger { background: #EF4444; color: white; padding: 0.4rem 0.8rem; border-radius: 0.35rem; border: none; cursor: pointer; font-weight: 600; }
        .alert { padding: 0.85rem 1.25rem; border-radius: 0.5rem; margin-bottom: 1.25rem; }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 0.75rem; border-bottom: 1px solid var(--border-color); font-size: 0.82rem; text-transform: uppercase; color: var(--text-muted); }
        td { padding: 0.75rem; border-bottom: 1px solid var(--border-color); font-size: 0.88rem; }
    </style>
</head>
<body>
    <!-- SIDEBAR ADMIN -->
    <aside class="sidebar">
        <div class="sidebar-brand" style="gap: 0.75rem;">
            <img src="{{ asset('images/farmaluz-logo.png') }}" alt="Logo Farmaluz" style="height: 38px; width: 38px; background: white; border-radius: 50%; padding: 2px;">
            <div>
                <div style="font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 800; color: #FFFFFF; line-height: 1;">FARMALUZ</div>
                <div style="font-size: 0.62rem; color: #E4007C; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;">Magistrales</div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.productos.index') }}" class="sidebar-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i> Productos
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categorias.index') }}" class="sidebar-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Categorías
                </a>
            </li>
            <li>
                <a href="{{ route('admin.zonas.index') }}" class="sidebar-link {{ request()->routeIs('admin.zonas.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-ramp-box"></i> Zonas de Envío
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pagos.index') }}" class="sidebar-link {{ request()->routeIs('admin.pagos.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-qrcode"></i> Pagos QR / Transf.
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reportes.movimientos') }}" class="sidebar-link {{ request()->routeIs('admin.reportes.movimientos') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i> Reporte Movimientos (Kardex)
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reportes.ventas') }}" class="sidebar-link {{ request()->routeIs('admin.reportes.ventas') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i> Reporte de Ventas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.configuracion.index') }}" class="sidebar-link {{ request()->routeIs('admin.configuracion.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Configuración Tienda
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tiendas.index') }}" class="sidebar-link {{ request()->routeIs('admin.tiendas.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-shop"></i> Mis Tiendas
                </a>
            </li>
            <li style="margin-top: 2rem; border-top: 1px solid #1E293B;">
                <a href="{{ route('tienda.inicio') }}" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver Tienda Pública
                </a>
            </li>
        </ul>
    </aside>

    <!-- ÁREA PRINCIPAL -->
    <div class="main-content">
        <header class="admin-header">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <h2 style="margin: 0; font-size: 1.15rem; font-weight: 800;">Panel de Control</h2>
                
                @php
                    $tenantMgr = app(\App\Services\TenantManager::class);
                    $tiendaActual = $tenantMgr->getTienda();
                    $misTiendas = auth()->check() ? auth()->user()->tiendas : collect();
                @endphp

                <!-- Selector Multi-Tienda -->
                <form action="{{ route('admin.tiendas.switch') }}" method="POST" id="form-switch-tienda" style="margin: 0; display: flex; align-items: center; gap: 0.5rem; background: #F8FAFC; padding: 0.35rem 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-color);">
                    @csrf
                    <label for="select-tienda" style="font-size: 0.8rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 0.35rem;">
                        <i class="fa-solid fa-store" style="color: var(--primary);"></i> Tienda:
                    </label>
                    <select name="tienda_id" id="select-tienda" onchange="this.form.submit()" style="border: 1px solid #CBD5E1; border-radius: 0.35rem; padding: 0.25rem 0.5rem; font-size: 0.85rem; font-weight: 600; color: #0F172A; background: white; cursor: pointer; outline: none;">
                        @foreach($misTiendas as $t)
                            <option value="{{ $t->id }}" {{ $tiendaActual && $tiendaActual->id == $t->id ? 'selected' : '' }}>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('admin.tiendas.create') }}" style="font-size: 0.82rem; font-weight: 600; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 0.35rem; background: #EFF6FF; padding: 0.4rem 0.8rem; border-radius: 0.4rem; border: 1px solid #BFDBFE;">
                    <i class="fa-solid fa-plus"></i> Nueva Tienda
                </a>
                <span style="font-size: 0.85rem; color: var(--text-muted);"><i class="fa-solid fa-circle-user"></i> {{ auth()->user()->name ?? 'Administrador' }}</span>
            </div>
        </header>

        <div class="admin-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @yield('contenido')
        </div>
    </div>
</body>
</html>
