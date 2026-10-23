<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'FARMALUZ - Preparados Magistrales y Farmacia Natural')</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #F9FBF9;
            --primary: #2B7A38;            /* Verde Botánico Farmaluz */
            --primary-dark: #1E5C29;       /* Verde Profundo */
            --primary-light: #E8F5E9;      /* Fondo Verde Suave */
            --secondary: #E4007C;          /* Magenta / Fucsia Magistral */
            --secondary-dark: #BA0065;
            --secondary-light: #FCE4EC;
            --leaf-green: #48B02C;         /* Verde Hoja Claro */
            --mortar-blue: #7E98C7;        /* Azul Lavanda Mortero */
            --text-main: #132A17;          /* Verde Muy Oscuro / Casi Negro */
            --text-muted: #52796F;
            --border-color: #E2EBE2;
            --card-bg: #FFFFFF;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }
        /* Header & Nav */
        .site-header {
            background: #FFFFFF;
            border-bottom: 2px solid var(--primary-light);
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 2px 12px rgba(43, 122, 56, 0.05);
        }
        .top-bar {
            background: #14381C;
            color: #C8E6C9;
            font-size: 0.82rem;
            padding: 0.45rem 0;
            border-bottom: 1px solid rgba(228, 0, 124, 0.3);
        }
        .header-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 0;
            gap: 1.5rem;
        }
        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: 0.02em;
        }
        .brand-logo-img {
            height: 52px;
            width: auto;
            object-fit: contain;
        }
        .brand-text-wrap {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }
        .brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 0.06em;
        }
        .brand-subtitle {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.62rem;
            font-weight: 700;
            color: var(--secondary);
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin-top: 0.2rem;
        }
        .search-box {
            flex: 1;
            max-width: 550px;
            position: relative;
        }
        .search-input {
            width: 100%;
            padding: 0.65rem 1rem 0.65rem 2.5rem;
            border: 1.5px solid var(--border-color);
            border-radius: 9999px;
            font-size: 0.92rem;
            outline: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
            background: #F8FAFC;
        }
        .search-input:focus {
            border-color: var(--secondary);
            background: #FFF;
            box-shadow: 0 0 0 4px rgba(228, 0, 124, 0.12), 0 1px 4px rgba(0,0,0,0.06);
        }
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            transition: color 0.2s;
        }
        .search-box:focus-within .search-icon {
            color: var(--secondary);
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        .cart-trigger {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.1rem;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 9999px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1.5px solid transparent;
            position: relative;
            overflow: hidden;
        }
        .cart-trigger::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.55), transparent);
            transform: skewX(-20deg);
            transition: left 0.5s ease;
        }
        .cart-trigger:hover::after {
            left: 140%;
        }
        .cart-trigger:hover {
            background: #DBEAFE;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(43,122,56,0.18);
            transform: translateY(-1px);
        }
        .cart-trigger:active { transform: scale(0.97); }
        .cart-badge {
            background: var(--secondary);
            color: white;
            border-radius: 9999px;
            padding: 0.15rem 0.55rem;
            font-size: 0.72rem;
            font-weight: 800;
            animation: cartBadgePop 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes cartBadgePop {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        /* =====================================================================
           SISTEMA DE BOTONES PREMIUM — Shimmer, Elevación & Pulsación Táctil
           ===================================================================== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.65rem 1.35rem;
            border-radius: 0.55rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(43,122,56,0.28);
            transition: transform 0.2s, box-shadow 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 65%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.35), transparent);
            transform: skewX(-20deg);
            transition: left 0.55s ease;
        }
        .btn-primary:hover::before { left: 140%; }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(43,122,56,0.35);
        }
        .btn-primary:active { transform: scale(0.97) translateY(0); box-shadow: 0 2px 8px rgba(43,122,56,0.2); }

        .btn-outline {
            background: transparent;
            color: var(--text-main);
            border: 1.5px solid var(--border-color);
            padding: 0.65rem 1.25rem;
            border-radius: 0.55rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.4,0,0.2,1);
        }
        .btn-outline:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 2px 10px rgba(43,122,56,0.1);
        }
        .btn-outline:active { transform: scale(0.97); }

        /* =====================================================================
           SISTEMA DE TARJETAS — Sombras Atmosféricas, Glow & Elevación 3D
           ===================================================================== */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.85rem;
            overflow: hidden;
            box-shadow:
                0 4px 20px -2px rgba(14,36,19,0.07),
                0 1px 3px 0 rgba(0,0,0,0.04);
            transition:
                transform 0.3s cubic-bezier(0.16,1,0.3,1),
                box-shadow 0.3s cubic-bezier(0.16,1,0.3,1),
                border-color 0.2s;
            position: relative;
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow:
                0 18px 36px -5px rgba(14,36,19,0.13),
                0 0 0 1px rgba(228,0,124,0.18),
                0 1px 3px rgba(0,0,0,0.04);
            border-color: rgba(228,0,124,0.25);
        }
        /* Efecto shine al pasar el cursor en toda la tarjeta */
        .card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 0.85rem;
            background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.5) 50%, transparent 60%);
            background-size: 200% 100%;
            background-position: -100% center;
            transition: background-position 0.6s ease;
            pointer-events: none;
            z-index: 0;
        }
        .card:hover::after { background-position: 200% center; }
        /* Flash alerts */
        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        /* Flash alerts mejorados */
        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 0.65rem;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: alertSlideIn 0.35s cubic-bezier(0.16,1,0.3,1) forwards;
        }
        @keyframes alertSlideIn {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; box-shadow: 0 2px 8px rgba(5,150,105,0.1); }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; box-shadow: 0 2px 8px rgba(239,68,68,0.1); }

        /* =====================================================================
           WHATSAPP FLOTANTE — Onda de Pulso Radial Continua
           ===================================================================== */
        .whatsapp-float {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            box-shadow: 0 6px 20px rgba(37,211,102,0.45);
            z-index: 200;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .whatsapp-float::before, .whatsapp-float::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2.5px solid rgba(37,211,102,0.5);
            animation: whatsappPulse 2.2s cubic-bezier(0.4,0,0.6,1) infinite;
        }
        .whatsapp-float::after { animation-delay: 1.1s; }
        @keyframes whatsappPulse {
            0%   { transform: scale(1);   opacity: 0.7; }
            100% { transform: scale(1.85); opacity: 0; }
        }
        .whatsapp-float:hover {
            transform: scale(1.12);
            box-shadow: 0 10px 28px rgba(37,211,102,0.55);
        }
        .whatsapp-float:active { transform: scale(1.05); }
        /* Footer */
        .site-footer {
            background: #0F172A;
            color: #94A3B8;
            padding: 3rem 0 1.5rem 0;
            margin-top: auto;
            border-top: 1px solid #1E293B;
        }

        /* =====================================================================
           ÍCONOS #E4007C — Micro-rebote Elástico & Aura Luminosa
           ===================================================================== */
        i.fa-solid, 
        i.fa-regular, 
        i.fa-brands:not(.fa-whatsapp),
        .search-icon i,
        .cart-trigger i {
            color: #E4007C !important;
            transition: transform 0.22s cubic-bezier(0.34,1.56,0.64,1), filter 0.2s;
            display: inline-block;
        }
        /* Micro-rebote al hover sobre el contenedor padre */
        .card:hover i.fa-solid,
        .card:hover i.fa-regular,
        a:hover > i.fa-solid,
        button:hover > i.fa-solid {
            transform: scale(1.22) rotate(5deg);
        }
        /* Iconos en círculos de beneficios ganan aura al hover de la tarjeta */
        .card:hover [style*='border-radius: 50%'] i.fa-solid {
            filter: drop-shadow(0 0 8px rgba(228,0,124,0.55));
        }
        /* Contraste en botones primarios */
        .btn-primary i, .btn-primary i.fa-solid {
            color: #FFFFFF !important;
            transition: transform 0.22s cubic-bezier(0.34,1.56,0.64,1);
        }
        .btn-primary:hover i.fa-solid { transform: translateX(3px); }
        .btn-outline i, .btn-outline i.fa-solid { color: #E4007C !important; }
        .whatsapp-float i.fa-whatsapp { color: #FFFFFF !important; }
        .site-footer i.fa-solid { color: #E4007C !important; }

        /* -----------------------------------------------------------
           4. MODAL SPLASH BIENVENIDA PROFESIONAL FARMALUZ (5s)
           ----------------------------------------------------------- */
        #farmaluz-splash {
            position: fixed;
            inset: 0;
            background: rgba(10, 25, 14, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            /* Estado inicial visible — NECESARIO para que transition tenga punto de partida */
            opacity: 1;
            visibility: visible;
            transition:
                opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                backdrop-filter 0.8s ease;
        }
        #farmaluz-splash.splash-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .splash-modal {
            background: #FFFFFF;
            border: 1px solid rgba(43, 122, 56, 0.2);
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(228, 0, 124, 0.15);
            max-width: 480px;
            width: 100%;
            position: relative;
            overflow: hidden;
            animation: modalPopIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            display: flex;
            flex-direction: column;
        }
        .splash-modal-header {
            background: linear-gradient(135deg, #0E2413 0%, #1E5C29 100%);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            text-align: center;
            position: relative;
            color: white;
        }
        .splash-modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            z-index: 2;
        }
        .splash-modal-close:hover {
            background: #E4007C;
            transform: scale(1.08);
        }
        .splash-logo-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 0.75rem;
        }
        .splash-logo-glow {
            position: absolute;
            inset: -8px;
            background: radial-gradient(circle, rgba(228, 0, 124, 0.4) 0%, rgba(43, 122, 56, 0) 70%);
            border-radius: 50%;
            animation: splashPulse 2.5s infinite alternate;
        }
        .splash-logo-img {
            position: relative;
            width: 76px;
            height: 76px;
            background: #FFFFFF;
            border-radius: 50%;
            padding: 6px;
            object-fit: contain;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
        }
        .splash-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: #FFFFFF;
            margin: 0 0 0.2rem 0;
        }
        .splash-tagline {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #E4007C;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin: 0;
        }
        .splash-modal-body {
            padding: 1.5rem 1.75rem;
            text-align: center;
            background: #FFFFFF;
        }
        .splash-welcome-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #132A17;
            margin-bottom: 0.5rem;
        }
        .splash-welcome-sub {
            font-size: 0.88rem;
            line-height: 1.6;
            color: #52796F;
            margin: 0 0 1.25rem 0;
        }
        /* Barra de progreso de 5 segundos en modal */
        .splash-progress-track {
            width: 100%;
            height: 4px;
            background: #F1F5F9;
            overflow: hidden;
            position: relative;
        }
        .splash-progress-bar {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            background: linear-gradient(90deg, #E4007C 0%, #2B7A38 100%);
            width: 0%;
            animation: splashLoad 5s linear forwards;
        }
        .splash-modal-footer {
            padding: 0.75rem 1.5rem 1.25rem 1.5rem;
            display: flex;
            justify-content: center;
            background: #FFFFFF;
        }
        .splash-btn-entrar {
            background: #E4007C;
            color: white;
            border: none;
            padding: 0.55rem 1.5rem;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(228, 0, 124, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .splash-btn-entrar:hover {
            background: #BA0065;
            transform: translateY(-1px);
        }

        @keyframes modalPopIn {
            0% { transform: scale(0.92); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes splashPulse {
            0% { transform: scale(0.95); opacity: 0.5; }
            100% { transform: scale(1.15); opacity: 0.9; }
        }
        @keyframes splashLoad {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        /* =====================================================================
           RESPONSIVE DESIGN COMPLETO: MÓVILES (IPHONE/ANDROID), TABLETS, DESKTOPS Y 4K/TV
           ===================================================================== */

        /* 1. DISPOSITIVOS MUY GRANDES / TV / PANTALLAS 2K & 4K (min-width: 1600px) */
        @media (min-width: 1600px) {
            .container {
                max-width: 1540px;
            }
            body {
                font-size: 16px;
            }
            .brand-title {
                font-size: 1.85rem;
            }
            .search-input {
                font-size: 1rem;
                padding: 0.8rem 1.25rem 0.8rem 2.8rem;
            }
        }

        /* 2. TABLETS Y LAPTOPS MEDIANAS (max-width: 1024px) */
        @media (max-width: 1024px) {
            .container {
                padding: 0 1rem;
            }
            .header-main {
                gap: 1rem;
            }
            .search-box {
                max-width: 380px;
            }
            .slide-title {
                font-size: 2.25rem !important;
            }
        }

        /* 3. TABLETS VERTICALES Y SMARTPHONES GRANDES (max-width: 768px) */
        @media (max-width: 768px) {
            /* Top bar */
            .top-bar {
                font-size: 0.74rem;
                padding: 0.35rem 0;
            }
            .top-bar .container {
                flex-direction: column;
                gap: 0.35rem;
                text-align: center;
            }
            .top-bar .container > div:first-child {
                display: none; /* Simplificar para pantallas pequeñas */
            }

            /* Header Principal */
            .header-main {
                flex-wrap: wrap;
                padding: 0.65rem 0;
                gap: 0.75rem;
            }
            .brand-logo-img {
                height: 42px;
            }
            .brand-title {
                font-size: 1.25rem;
            }
            .brand-subtitle {
                font-size: 0.55rem;
            }

            /* Caja de búsqueda a ancho completo debajo del logo */
            .search-box {
                order: 3;
                width: 100%;
                max-width: 100%;
                margin: 0;
            }
            .search-input {
                font-size: 0.85rem;
                padding: 0.55rem 1rem 0.55rem 2.3rem;
            }

            /* Acciones del header */
            .header-actions {
                gap: 0.75rem;
            }
            .header-actions a[href*="rastreo"] {
                display: none; /* Se mantiene en footer para móviles */
            }
            .cart-trigger {
                padding: 0.45rem 0.85rem;
                font-size: 0.82rem;
            }

            /* Hero Carousel */
            #farmaluz-hero-carousel {
                min-height: 420px !important;
            }
            .hero-slide {
                padding: 2rem 1.5rem !important;
                background-position: center center !important;
            }
            .slide-content-card {
                max-width: 100% !important;
                text-align: left;
            }
            .slide-title {
                font-size: 1.85rem !important;
                line-height: 1.2 !important;
            }
            .slide-desc {
                font-size: 0.95rem !important;
                margin-bottom: 1.5rem !important;
            }
            .hero-nav-arrow {
                width: 36px !important;
                height: 36px !important;
                font-size: 0.9rem !important;
            }
            .hero-nav-arrow.left { left: 0.65rem !important; }
            .hero-nav-arrow.right { right: 0.65rem !important; }
            .hero-dots {
                left: 1.5rem !important;
                bottom: 1rem !important;
            }

            /* Grilla de Catálogo con Sidebar */
            div[style*="grid-template-columns: 280px 1fr"] {
                grid-template-columns: 1fr !important;
                gap: 1.25rem !important;
            }

            /* Footer */
            .site-footer div[style*="grid-template-columns: repeat(auto-fit"] {
                grid-template-columns: 1fr !important;
                gap: 2rem !important;
            }
        }

        /* 4. SMARTPHONES / IPHONES / DISPOSITIVOS COMPACTOS (max-width: 480px) */
        @media (max-width: 480px) {
            .container {
                padding: 0 0.75rem;
            }
            .brand-title {
                font-size: 1.15rem;
            }
            .btn-hero {
                width: 100%;
                justify-content: center;
                padding: 0.75rem 1.25rem !important;
                font-size: 0.9rem !important;
            }
            .slide-actions {
                flex-direction: column;
                gap: 0.6rem !important;
                width: 100%;
            }
            .slide-title {
                font-size: 1.55rem !important;
            }
            .slide-badges {
                gap: 0.4rem !important;
            }
            .badge-pill {
                font-size: 0.72rem !important;
                padding: 0.25rem 0.65rem !important;
            }
            #farmaluz-hero-carousel {
                min-height: 460px !important;
            }
            .whatsapp-float {
                width: 50px !important;
                height: 50px !important;
                font-size: 1.5rem !important;
                bottom: 1.25rem !important;
                right: 1.25rem !important;
            }
            /* Modal Splash en móviles pequeños */
            .splash-modal {
                border-radius: 1rem;
                margin: 0.5rem;
            }
            .splash-welcome-title {
                font-size: 1rem !important;
            }
            .splash-welcome-sub {
                font-size: 0.8rem !important;
            }
        }
    </style>
</head>
<body>
    <!-- Top info bar -->
    <div class="top-bar">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div><i class="fa-solid fa-truck-fast"></i> Envíos a toda Bolivia (La Paz, Cbba, Santa Cruz y resto del país)</div>
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <a href="{{ route('tienda.catalogo.pdf') }}" style="color: #FCE4EC; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="fa-solid fa-file-pdf" style="color: #E4007C;"></i> Descargar Catálogo PDF
                </a>
                <a href="{{ route('admin.dashboard') }}" style="color: #CBD5E1; text-decoration: none;"><i class="fa-solid fa-lock"></i> Panel Administrativo</a>
            </div>
        </div>
    </div>

    <!-- Header Principal -->
    <header class="site-header">
        <div class="container">
            <div class="header-main">
                <a href="{{ route('tienda.inicio') }}" class="brand-logo">
                    <img src="{{ asset('images/farmaluz-logo.png') }}" alt="Farmaluz Preparados Magistrales" class="brand-logo-img">
                    <div class="brand-text-wrap">
                        <span class="brand-title">FARMALUZ</span>
                        <span class="brand-subtitle">Preparados Magistrales</span>
                    </div>
                </a>

                <form action="{{ route('tienda.catalogo') }}" method="GET" class="search-box">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar tinturas, cremas magistrales, jarabes naturales, aceites..." class="search-input">
                </form>

                <div class="header-actions">
                    <a href="{{ route('tienda.rastreo') }}" style="text-decoration: none; color: #475569; font-weight: 500; font-size: 0.88rem;">
                        <i class="fa-solid fa-truck"></i> Rastrear Pedido
                    </a>

                    @auth
                        <a href="{{ route('cliente.perfil') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 0.88rem;">
                            <i class="fa-solid fa-circle-user"></i> Mi Cuenta
                        </a>
                    @else
                        <a href="{{ route('cliente.login') }}" style="text-decoration: none; color: #475569; font-weight: 500; font-size: 0.88rem;">
                            <i class="fa-solid fa-user"></i> Ingresar
                        </a>
                    @endauth
                    
                    @php
                        $carritoItems = session()->get('carrito', []);
                        $totalItems = array_sum(array_column($carritoItems, 'cantidad'));
                    @endphp
                    <a href="{{ route('carrito.index') }}" class="cart-trigger">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Carrito</span>
                        <span class="cart-badge">{{ $totalItems }}</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Notificaciones del sistema -->
    <div class="container" style="margin-top: 1rem;">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Contenido dinámico -->
    <main style="flex: 1;">
        @yield('contenido')
    </main>

    <!-- Botón Flotante WhatsApp -->
    @php
        $whatsappNum = \App\Models\Configuracion::get('telefono_whatsapp', '59170012345');
        $whatsappMsg = urlencode(\App\Models\Configuracion::get('mensaje_whatsapp_default', 'Hola Farmaluz, deseo consultar sobre un preparado magistral o producto natural'));
    @endphp
    <a href="https://wa.me/{{ $whatsappNum }}?text={{ $whatsappMsg }}" target="_blank" class="whatsapp-float" title="Consultar a Farmaluz por WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- Footer -->
    <footer class="site-footer" style="background: #0E2413; border-top: 3px solid var(--primary);">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <img src="{{ asset('images/farmaluz-logo.png') }}" alt="Logo Farmaluz" style="height: 44px; background: white; border-radius: 50%; padding: 2px;">
                        <h3 style="color: white; margin: 0; font-size: 1.2rem; font-family: 'Playfair Display', serif;">FARMALUZ</h3>
                    </div>
                    <p style="font-size: 0.88rem; line-height: 1.6; color: #A5D6A7;">Preparados magistrales, medicina natural, fitoterapia y dermofarmacia con el más alto rigor de pureza y formulación especializada.</p>
                </div>
                <div>
                    <h4 style="color: white; font-size: 0.95rem; margin-bottom: 0.75rem;">Enlaces Rápidos</h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.88rem; line-height: 2;">
                        <li><a href="{{ route('tienda.inicio') }}" style="color: #C8E6C9; text-decoration: none;">Inicio</a></li>
                        <li><a href="{{ route('tienda.catalogo') }}" style="color: #C8E6C9; text-decoration: none;">Catálogo de Preparados</a></li>
                        <li><a href="{{ route('carrito.index') }}" style="color: #C8E6C9; text-decoration: none;">Mi Carrito</a></li>
                        <li><a href="{{ route('admin.dashboard') }}" style="color: #C8E6C9; text-decoration: none;">Acceso Personal Farmacéutico</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="color: white; font-size: 0.95rem; margin-bottom: 0.75rem;">Medios de Pago Habilitados</h4>
                    <p style="font-size: 0.88rem; color: #A5D6A7;">QR Simple Interbancario sin contacto, Transferencia bancaria directa y pago contra entrega.</p>
                    <div style="font-size: 1.5rem; display: flex; gap: 0.8rem; color: #E8F5E9; margin-top: 0.5rem;">
                        <i class="fa-solid fa-qrcode" title="QR Simple"></i>
                        <i class="fa-solid fa-building-columns" title="Transferencia"></i>
                        <i class="fa-solid fa-money-bill-wave" title="Efectivo"></i>
                    </div>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.5rem; text-align: center; font-size: 0.82rem; color: #81C784;">
                © 2026 FARMALUZ Preparados Magistrales. Farmacia Natural & Bienestar Integral. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <!-- 4. MODAL SPLASH BIENVENIDA PROFESIONAL FARMALUZ (5s) -->
    <div id="farmaluz-splash" aria-hidden="false">
        <div class="splash-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="splash-modal-header">
                <button type="button" class="splash-modal-close" onclick="cerrarSplash()" title="Cerrar modal" aria-label="Cerrar">
                    &times;
                </button>
                <div class="splash-logo-wrap">
                    <div class="splash-logo-glow"></div>
                    <img src="{{ asset('images/farmaluz-logo.png') }}" alt="Logo Farmaluz" class="splash-logo-img">
                </div>
                <h2 id="modal-title" class="splash-brand">FARMALUZ</h2>
                <p class="splash-tagline">Preparados Magistrales</p>
            </div>

            <div class="splash-progress-track">
                <div class="splash-progress-bar"></div>
            </div>

            <div class="splash-modal-body">
                <div class="splash-welcome-title">
                    ¡Bienvenidos a su Farmacia Natural!
                </div>
                <p class="splash-welcome-sub">
                    Fórmulas galénicas personalizadas, fitoterapia pura y dermocosmética con los más altos estándares para su bienestar integral.
                </p>

                <div class="splash-modal-footer">
                    <button type="button" class="splash-btn-entrar" onclick="cerrarSplash()">
                        Ingresar a la Tienda <i class="fa-solid fa-arrow-right" style="color: white !important;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        /**
         * SPLASH — Solo se muestra UNA VEZ por sesión (primera visita).
         * Se usa sessionStorage para marcar que ya fue presentado.
         * En cualquier página subsiguiente (categorías, carrito, etc.)
         * el elemento se elimina del DOM antes del primer render, sin parpadeo.
         */
        (function() {
            const STORAGE_KEY = 'farmaluz_splash_shown';
            const splash = document.getElementById('farmaluz-splash');
            if (!splash) return;

            if (sessionStorage.getItem(STORAGE_KEY)) {
                // Ya se mostró en esta sesión → quitar inmediatamente, sin animación
                splash.style.display = 'none';
                return;
            }

            // Primera visita: marcar y mostrar normalmente
            sessionStorage.setItem(STORAGE_KEY, '1');
        })();

        function cerrarSplash() {
            const splash = document.getElementById('farmaluz-splash');
            if (!splash || splash.classList.contains('splash-hidden')) return;

            // Activar fade-out mediante clase CSS
            splash.classList.add('splash-hidden');

            // Quitar del DOM al terminar la transición de opacidad
            splash.addEventListener('transitionend', function handler(e) {
                if (e.propertyName !== 'opacity') return;
                splash.style.display = 'none';
                splash.removeEventListener('transitionend', handler);
            });

            // Fallback por si transitionend no dispara (tab en segundo plano, etc.)
            setTimeout(function() { splash.style.display = 'none'; }, 1200);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const splash = document.getElementById('farmaluz-splash');
            // Si ya estaba oculto (sesión previa), no hacer nada más
            if (!splash || splash.style.display === 'none') return;

            // Cierre automático a los 5 segundos
            setTimeout(cerrarSplash, 5000);

            // Cerrar al hacer click en el backdrop (fuera del modal)
            splash.addEventListener('click', function(e) {
                if (e.target === splash) cerrarSplash();
            });
        });
    </script>
</body>
</html>
