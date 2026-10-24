<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Panel Administrativo - FARMALUZ')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,800;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #F4F7F4;
            --primary: #1E5C29;
            --primary-hover: #15451D;
            --primary-light: #EBF4EC;
            --secondary: #E4007C;
            --secondary-hover: #C5006B;
            --secondary-light: #FDF2F8;
            --sidebar-bg: #0A1C10;
            --sidebar-hover: rgba(255, 255, 255, 0.06);
            --card-bg: #FFFFFF;
            --text-main: #0F2414;
            --text-muted: #5A7361;
            --border-color: #E2ECE2;
            --border-focus: #1E5C29;
            --shadow-sm: 0 1px 2px rgba(15, 36, 20, 0.04);
            --shadow-md: 0 4px 16px -2px rgba(15, 36, 20, 0.07), 0 2px 6px -1px rgba(15, 36, 20, 0.04);
            --shadow-lg: 0 12px 30px -4px rgba(15, 36, 20, 0.1);
            --radius-sm: 0.5rem;
            --radius-md: 0.85rem;
            --radius-lg: 1.15rem;
        }

        * {
            box-sizing: border-box;
            transition: border-color 0.15s ease, background-color 0.15s ease;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* SIDEBAR PROFESIONAL */
        .sidebar {
            width: 275px;
            background: var(--sidebar-bg);
            color: #94A3B8;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
        }

        .sidebar-brand {
            padding: 1.65rem 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: linear-gradient(180deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0) 100%);
        }

        .sidebar-brand-badge {
            display: inline-block;
            font-size: 0.65rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 800;
            color: #FF80BF;
            background: rgba(228, 0, 124, 0.15);
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            border: 1px solid rgba(228, 0, 124, 0.3);
        }

        .sidebar-menu-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.85rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .sidebar-section-title {
            font-size: 0.68rem;
            font-weight: 800;
            color: #64826D;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.85rem 0.75rem 0.35rem 0.75rem;
            margin: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.7rem 0.95rem;
            color: #A3B8A8;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            border-radius: var(--radius-sm);
            position: relative;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 0.95rem;
            color: #6B8B72;
            transition: color 0.2s;
        }

        .sidebar-link:hover {
            color: #FFFFFF;
            background: var(--sidebar-hover);
        }

        .sidebar-link:hover i {
            color: #4ADE80;
        }

        .sidebar-link.active {
            color: #FFFFFF;
            background: linear-gradient(90deg, rgba(30, 92, 41, 0.45) 0%, rgba(30, 92, 41, 0.15) 100%);
            border: 1px solid rgba(74, 222, 128, 0.25);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .sidebar-link.active i {
            color: #4ADE80;
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: -0.85rem;
            top: 25%;
            bottom: 25%;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: #4ADE80;
        }

        /* ÁREA PRINCIPAL */
        .main-content {
            margin-left: 275px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* HEADER ELEGANTE */
        .admin-header {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 0.95rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 4px rgba(15, 36, 20, 0.03);
        }

        .admin-body {
            padding: 2.25rem;
            flex: 1;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
        }

        /* TARJETAS PREMIUM */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.75rem;
            box-shadow: var(--shadow-md);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        /* BOTONES DE ALTA GAMA */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 0.65rem 1.25rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.88rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(30, 92, 41, 0.2);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 92, 41, 0.3);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-hover) 100%);
            color: white;
            padding: 0.65rem 1.25rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.88rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(228, 0, 124, 0.2);
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(228, 0, 124, 0.3);
            color: white;
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 1px solid var(--border-color);
            padding: 0.6rem 1.15rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.86rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-hover);
        }

        .btn-success {
            background: #059669;
            color: white;
            padding: 0.45rem 0.9rem;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: background-color 0.15s;
        }
        .btn-success:hover { background: #047857; }

        .btn-danger {
            background: #DC2626;
            color: white;
            padding: 0.45rem 0.9rem;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: background-color 0.15s;
        }
        .btn-danger:hover { background: #B91C1C; }

        /* ALERTAS MODERNAS */
        .alert {
            padding: 0.95rem 1.4rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: #F0FDF4;
            color: #166534;
            border: 1px solid #BBF7D0;
        }

        .alert-error {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* TABLAS ESTILIZADAS */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
        }

        th {
            background: #F9FAF9;
            text-align: left;
            padding: 0.95rem 1.15rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            font-weight: 800;
            white-space: nowrap;
        }

        td {
            padding: 1.05rem 1.15rem;
            border-bottom: 1px solid #EDF3ED;
            font-size: 0.88rem;
            color: #1F2937;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background-color 0.15s ease;
        }

        tbody tr:hover {
            background-color: #F8FAF8;
        }

        /* BADGES */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .badge-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .badge-warning { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
        .badge-danger { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        .badge-info { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }
        .badge-neutral { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }

        /* FORMULARIOS */
        .form-control {
            width: 100%;
            padding: 0.65rem 0.9rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.88rem;
            color: var(--text-main);
            background: white;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-control:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(30, 92, 41, 0.12);
        }

        /* MENU TOGGLE PARA MÓVIL */
        .menu-toggle {
            display: none;
            background: none;
            border: 1px solid var(--border-color);
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            color: var(--text-main);
            font-size: 1.1rem;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .menu-toggle {
                display: inline-flex;
            }
            .admin-body {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR ADMIN -->
    <aside class="sidebar" id="sidebarAdmin">
        <div class="sidebar-brand">
            <img src="{{ asset('images/farmaluz-logo.png') }}" alt="Logo Farmaluz" style="height: 42px; width: 42px; background: white; border-radius: 50%; padding: 2px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
            <div>
                <div style="font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 800; color: #FFFFFF; line-height: 1; letter-spacing: 0.02em;">FARMALUZ</div>
                <div style="margin-top: 0.25rem;">
                    <span class="sidebar-brand-badge">Magistrales</span>
                </div>
            </div>
        </div>

        <div class="sidebar-menu-wrapper">
            <p class="sidebar-section-title">Principal</p>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>

            <p class="sidebar-section-title" style="margin-top: 1rem;">Catálogo & Inventario</p>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.productos.index') }}" class="sidebar-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span>Productos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categorias.index') }}" class="sidebar-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-tags"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reportes.movimientos') }}" class="sidebar-link {{ request()->routeIs('admin.reportes.movimientos') ? 'active' : '' }}">
                        <i class="fa-solid fa-warehouse"></i>
                        <span>Kardex de Stock</span>
                    </a>
                </li>
            </ul>

            <p class="sidebar-section-title" style="margin-top: 1rem;">Ventas & Cobros</p>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.pagos.index') }}" class="sidebar-link {{ request()->routeIs('admin.pagos.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Validar Pagos QR</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reportes.ventas') }}" class="sidebar-link {{ request()->routeIs('admin.reportes.ventas') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Reporte de Ventas</span>
                    </a>
                </li>
            </ul>

            <p class="sidebar-section-title" style="margin-top: 1rem;">Operaciones & Negocio</p>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.zonas.index') }}" class="sidebar-link {{ request()->routeIs('admin.zonas.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                        <span>Zonas de Envío</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.configuracion.index') }}" class="sidebar-link {{ request()->routeIs('admin.configuracion.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Configuración Tienda</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tiendas.index') }}" class="sidebar-link {{ request()->routeIs('admin.tiendas.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-shop"></i>
                        <span>Mis Tiendas</span>
                    </a>
                </li>
            </ul>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.08);">
                <a href="{{ route('tienda.inicio') }}" target="_blank" class="sidebar-link" style="color: #4ADE80;">
                    <i class="fa-solid fa-arrow-up-right-from-square" style="color: #4ADE80;"></i>
                    <span>Ver Tienda Online</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- ÁREA PRINCIPAL -->
    <div class="main-content">
        <header class="admin-header">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button type="button" class="menu-toggle" onclick="toggleSidebar()" aria-label="Abrir Menú">
                    <i class="fa-solid fa-bars"></i>
                </button>

                @php
                    $tenantMgr = app(\App\Services\TenantManager::class);
                    $tiendaActual = $tenantMgr->getTienda();
                    $misTiendas = auth()->check() ? auth()->user()->tiendas : collect();
                @endphp

                <!-- Selector de Tienda Activa -->
                <form action="{{ route('admin.tiendas.switch') }}" method="POST" id="form-switch-tienda" style="margin: 0; display: flex; align-items: center; gap: 0.5rem; background: #F8FAFC; padding: 0.35rem 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    @csrf
                    <span style="font-size: 0.78rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 0.35rem;">
                        <i class="fa-solid fa-store" style="color: var(--primary);"></i> Tienda:
                    </span>
                    <select name="tienda_id" id="select-tienda" onchange="this.form.submit()" style="border: 1px solid #CBD5E1; border-radius: 0.35rem; padding: 0.25rem 0.6rem; font-size: 0.84rem; font-weight: 700; color: #0F172A; background: white; cursor: pointer; outline: none;">
                        @foreach($misTiendas as $t)
                            <option value="{{ $t->id }}" {{ $tiendaActual && $tiendaActual->id == $t->id ? 'selected' : '' }}>
                                {{ $t->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Perfil & Logout -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('admin.tiendas.create') }}" class="btn-outline" style="padding: 0.45rem 0.85rem; font-size: 0.8rem; border-radius: var(--radius-sm);">
                    <i class="fa-solid fa-plus" style="color: var(--primary);"></i>
                    <span>Nueva Tienda</span>
                </a>

                <div style="display: flex; align-items: center; gap: 0.65rem; padding-left: 0.5rem; border-left: 1px solid var(--border-color);">
                    <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div style="line-height: 1.2;">
                        <span style="display: block; font-size: 0.84rem; font-weight: 700; color: #0F172A;">{{ auth()->user()->name ?? 'Administrador' }}</span>
                        <span style="display: block; font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Regente Farmacéutico</span>
                    </div>
                </div>

                <form action="{{ route('cliente.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-outline" style="padding: 0.45rem 0.75rem; color: #64748B;" title="Cerrar Sesión">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span style="font-size: 0.8rem;">Salir</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="admin-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check" style="font-size: 1.1rem;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('contenido')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarAdmin');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>

