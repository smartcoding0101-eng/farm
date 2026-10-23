@extends('layouts.tienda')

@section('titulo', 'FARMALUZ - Preparados Magistrales y Farmacia Natural')

@section('contenido')
<div class="container" style="padding-top: 1.5rem; padding-bottom: 4rem;">

    <!-- 1. HERO DINÁMICO FARMALUZ (TRANSICIÓN AUTOMÁTICA CADA 3 SEGUNDOS - COLOR REAL SIN DIFUMINADO) -->
    <div id="farmaluz-hero-carousel" class="card" style="margin-bottom: 2.5rem; position: relative; border-radius: 1.25rem; overflow: hidden; border: none; box-shadow: 0 15px 35px -10px rgba(43, 122, 56, 0.25); min-height: 490px;">
        
        <!-- SLIDE 1: Preparados Magistrales - Imagen Real Pura -->
        <div class="hero-slide active" style="background-image: url('{{ asset('images/farmaluz-banner.jpg') }}');">
            <div class="slide-content-card">
                <div class="slide-badges">
                    <span class="badge-pill magenta">
                        <i class="fa-solid fa-mortar-pestle"></i> Preparados Magistrales
                    </span>
                    <span class="badge-pill green">
                        <i class="fa-solid fa-leaf"></i> 100% Extractos Puros
                    </span>
                </div>
                <h1 class="slide-title">Salud Natural & Fórmulas Magistrales a Tu Medida</h1>
                <p class="slide-desc">Fórmulas farmacéuticas personalizadas elaboradas con el más riguroso control botánico para tu bienestar integral.</p>
                <div class="slide-actions">
                    <a href="{{ route('tienda.catalogo') }}" class="btn-primary btn-hero green-btn">
                        <i class="fa-solid fa-mortar-pestle"></i> Ver Catálogo
                    </a>
                    <a href="{{ route('tienda.rastreo') }}" class="btn-outline btn-hero dark-outline">
                        <i class="fa-solid fa-truck-medical"></i> Rastrear Pedido
                    </a>
                </div>
            </div>
        </div>

        <!-- SLIDE 2: Fitoterapia y Extractos - Imagen Real Pura -->
        <div class="hero-slide" style="background-image: url('{{ asset('images/farmaluz-slide-2.jpg') }}');">
            <div class="slide-content-card">
                <div class="slide-badges">
                    <span class="badge-pill magenta">
                        <i class="fa-solid fa-droplet"></i> Tinturas Madre & Gotas
                    </span>
                    <span class="badge-pill green">
                        <i class="fa-solid fa-seedling"></i> Fitoterapia Pura
                    </span>
                </div>
                <h1 class="slide-title">El Poder Curativo de las Plantas Medicinales</h1>
                <p class="slide-desc">Aceites esenciales, jarabes bronquiales, extractos de manzanilla, eucalipto y caléndula de máxima pureza.</p>
                <div class="slide-actions">
                    <a href="{{ route('tienda.catalogo') }}" class="btn-primary btn-hero magenta-btn">
                        <i class="fa-solid fa-leaf"></i> Explorar Fitoterapia
                    </a>
                    <a href="https://wa.me/59170012345?text={{ urlencode('Hola Farmaluz, quiero consultar sobre preparados de fitoterapia') }}" target="_blank" class="btn-outline btn-hero dark-outline">
                        <i class="fa-brands fa-whatsapp"></i> Asesoría Farmacéutica
                    </a>
                </div>
            </div>
        </div>

        <!-- SLIDE 3: Dermofarmacia y Cosmética - Imagen Real Pura -->
        <div class="hero-slide" style="background-image: url('{{ asset('images/farmaluz-slide-3.jpg') }}');">
            <div class="slide-content-card">
                <div class="slide-badges">
                    <span class="badge-pill magenta">
                        <i class="fa-solid fa-spa"></i> Dermocosmética
                    </span>
                    <span class="badge-pill green">
                        <i class="fa-solid fa-shield-heart"></i> Libres de Químicos Nocivos
                    </span>
                </div>
                <h1 class="slide-title">Dermofarmacia Natural & Cuidado Terapéutico</h1>
                <p class="slide-desc">Cremas emolientes, pomadas de árnica, geles regeneradores con aloe vera y serums antiedad botánicos.</p>
                <div class="slide-actions">
                    <a href="{{ route('tienda.catalogo') }}" class="btn-primary btn-hero green-btn">
                        <i class="fa-solid fa-sparkles"></i> Ver Dermofarmacia
                    </a>
                    <a href="{{ route('tienda.catalogo') }}" class="btn-outline btn-hero dark-outline">
                        <i class="fa-solid fa-bag-shopping"></i> Comprar Ahora
                    </a>
                </div>
            </div>
        </div>

        <!-- Controles de Flecha Anterior / Siguiente -->
        <button id="hero-prev-btn" class="hero-nav-arrow left" aria-label="Anterior">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button id="hero-next-btn" class="hero-nav-arrow right" aria-label="Siguiente">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <!-- Indicadores / Dots de Progreso y Timer -->
        <div class="hero-dots">
            <button class="hero-dot active" data-index="0" aria-label="Slide 1">
                <span class="dot-progress"></span>
            </button>
            <button class="hero-dot" data-index="1" aria-label="Slide 2">
                <span class="dot-progress"></span>
            </button>
            <button class="hero-dot" data-index="2" aria-label="Slide 3">
                <span class="dot-progress"></span>
            </button>
        </div>
    </div>

    <!-- Estilos específicos del Carousel Dinámico Farmaluz -->
    <style>
        #farmaluz-hero-carousel {
            position: relative;
            background: #0E2413;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.8s;
            background-size: cover;
            background-position: center right;
            display: flex;
            align-items: center;
            padding: 3.5rem 4rem;
            color: white;
            z-index: 1;
        }
        .hero-slide.active {
            opacity: 1;
            visibility: visible;
            z-index: 2;
        }
        .slide-content-card {
            max-width: 620px;
            background: transparent !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            transform: translateY(12px);
            opacity: 0;
            transition: transform 0.6s ease-out 0.2s, opacity 0.6s ease-out 0.2s;
            color: #132A17;
        }
        .hero-slide.active .slide-content-card {
            transform: translateY(0);
            opacity: 1;
        }
        .slide-badges {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }
        .slide-content-card .slide-title {
            color: #132A17;
            font-size: 2.75rem;
            text-shadow: 0 1px 3px rgba(255, 255, 255, 0.8), 0 2px 12px rgba(255, 255, 255, 0.9);
            margin: 0 0 1rem 0;
        }
        .slide-content-card .slide-desc {
            color: #1F2937;
            font-size: 1.15rem;
            line-height: 1.6;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            font-weight: 500;
        }
        .dark-outline {
            color: #132A17 !important;
            border: 1.5px solid #CBD5E1 !important;
            background: white !important;
        }
        .dark-outline:hover {
            border-color: var(--primary) !important;
            color: var(--primary) !important;
        }
        .badge-pill {
            backdrop-filter: blur(8px);
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .badge-pill.magenta {
            background: rgba(228,0,124,0.3);
            border: 1px solid rgba(228,0,124,0.6);
            color: #FCE4EC;
        }
        .badge-pill.green {
            background: rgba(72,176,44,0.3);
            border: 1px solid rgba(72,176,44,0.6);
            color: #C8E6C9;
        }
        .slide-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin: 0 0 1.25rem 0;
            letter-spacing: -0.01em;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .slide-desc {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #E8F5E9;
            margin-bottom: 2.25rem;
            text-shadow: 0 1px 4px rgba(0,0,0,0.4);
        }
        .slide-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .btn-hero {
            padding: 0.95rem 2rem;
            font-size: 1rem;
            border-radius: 9999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-hero::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 65%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.28), transparent);
            transform: skewX(-20deg);
            transition: left 0.55s ease;
        }
        .btn-hero:hover::before { left: 140%; }
        .green-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(43,122,56,0.45);
        }
        .green-btn:hover {
            box-shadow: 0 14px 32px rgba(43,122,56,0.55);
            transform: translateY(-3px);
        }
        .green-btn:active { transform: scale(0.97); }
        .magenta-btn {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(228,0,124,0.40);
        }
        .magenta-btn:hover {
            box-shadow: 0 14px 32px rgba(228,0,124,0.52);
            transform: translateY(-3px);
        }
        .magenta-btn:active { transform: scale(0.97); }
        .btn-outline.btn-hero {
            color: white;
            border: 1.5px solid rgba(255,255,255,0.45);
            backdrop-filter: blur(8px);
            background: rgba(255,255,255,0.12);
        }
        .btn-outline.btn-hero:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.7);
            transform: translateY(-3px);
        }
        /* Hero Navigation Arrows — Glassmorphism Premium */
        .hero-nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            cursor: pointer;
            z-index: 10;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0.8;
        }
        .hero-nav-arrow:hover {
            opacity: 1;
            background: var(--secondary);
            border-color: var(--secondary);
            box-shadow: 0 0 20px rgba(228,0,124,0.45);
            transform: translateY(-50%) scale(1.12);
        }
        .hero-nav-arrow:active { transform: translateY(-50%) scale(1.04); }
        .hero-nav-arrow.left { left: 1.5rem; }
        .hero-nav-arrow.right { right: 1.5rem; }

        /* Indicadores Dots con barra de progreso */
        .hero-dots {
            position: absolute;
            bottom: 1.75rem;
            left: 4rem;
            display: flex;
            gap: 0.6rem;
            z-index: 10;
        }
        .hero-dot {
            width: 32px;
            height: 6px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.3);
            border: none;
            padding: 0;
            cursor: pointer;
            overflow: hidden;
            position: relative;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hero-dot:hover { background: rgba(255, 255, 255, 0.5); }
        .hero-dot.active {
            width: 56px;
            background: rgba(255, 255, 255, 0.5);
        }
        .hero-dot .dot-progress {
            display: block;
            width: 0%;
            height: 100%;
            background: var(--secondary);
            border-radius: 9999px;
        }
        .hero-dot.active .dot-progress {
            animation: heroDotProgress 3s linear forwards;
        }
        @keyframes heroDotProgress {
            0%   { width: 0%; }
            100% { width: 100%; }
        }

        /* =====================================================================
           ANIMACIONES GLOBALES DE INICIO — GPU Aceleradas 60fps
           ===================================================================== */
        @keyframes badgePulseSale {
            0%, 100% { box-shadow: 0 0 0 0 rgba(228,0,124,0); }
            50%       { box-shadow: 0 0 0 6px rgba(228,0,124,0.18); }
        }

        /* Glow en círculos de categoría al hacer hover en la tarjeta */
        .card:hover .cat-icon-circle {
            box-shadow: 0 6px 20px rgba(228,0,124,0.35) !important;
            transform: scale(1.12);
        }

        /* Cronómetro de Oferta Relámpago */
        #cronometro {
            font-family: 'Plus Jakarta Sans', monospace;
            letter-spacing: 0.08em;
            border-radius: 0.5rem;
            font-weight: 800;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }
    </style>

    <!-- Script del Carousel con Rotación Cada 3 Segundos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            const prevBtn = document.getElementById('hero-prev-btn');
            const nextBtn = document.getElementById('hero-next-btn');
            const carousel = document.getElementById('farmaluz-hero-carousel');
            
            if (!slides.length) return;

            let currentIndex = 0;
            let slideInterval = null;
            const INTERVAL_TIME = 3000; // Exactamente 3 segundos

            function resetDotAnimations() {
                dots.forEach(dot => {
                    dot.classList.remove('active');
                    const progress = dot.querySelector('.dot-progress');
                    if (progress) {
                        progress.style.animation = 'none';
                        void progress.offsetHeight; // Forzar reflow para reiniciar animación limpia
                        progress.style.animation = '';
                    }
                });
            }

            function goToSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                resetDotAnimations();

                currentIndex = (index + slides.length) % slides.length;

                slides[currentIndex].classList.add('active');
                dots[currentIndex].classList.add('active');
            }

            function nextSlide() {
                goToSlide(currentIndex + 1);
            }

            function prevSlide() {
                goToSlide(currentIndex - 1);
            }

            function startAutoPlay() {
                stopAutoPlay();
                slideInterval = setInterval(nextSlide, INTERVAL_TIME);
            }

            function stopAutoPlay() {
                if (slideInterval) {
                    clearInterval(slideInterval);
                    slideInterval = null;
                }
            }

            // Listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    startAutoPlay();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    startAutoPlay();
                });
            }

            dots.forEach(dot => {
                dot.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-index'));
                    goToSlide(idx);
                    startAutoPlay();
                });
            });

            // Pausar al hacer hover para facilitar lectura o click
            if (carousel) {
                carousel.addEventListener('mouseenter', stopAutoPlay);
                carousel.addEventListener('mouseleave', startAutoPlay);
            }

            // Inicializar animación del primer slide e iniciar ciclo de 3 segundos
            goToSlide(0);
            startAutoPlay();
        });
    </script>

    <!-- 2. BARRA DE BENEFICIOS CLAVE -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 3.5rem;">
        <div class="card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-radius: 0.75rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #FCE4EC; color: #E4007C; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                <i class="fa-solid fa-mortar-pestle"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800;">Fórmulas Magistrales</h4>
                <p style="margin: 0.15rem 0 0 0; font-size: 0.82rem; color: var(--text-muted);">Elaboración individualizada y precisa</p>
            </div>
        </div>

        <div class="card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-radius: 0.75rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #E8F5E9; color: #2B7A38; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                <i class="fa-solid fa-leaf"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800;">Plantas Medicinales</h4>
                <p style="margin: 0.15rem 0 0 0; font-size: 0.82rem; color: var(--text-muted);">Extractos estandarizados y puros</p>
            </div>
        </div>

        <div class="card" style="padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-radius: 0.75rem;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: #E8F0FE; color: #7E98C7; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0;">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 800;">Pago QR Bancario</h4>
                <p style="margin: 0.15rem 0 0 0; font-size: 0.82rem; color: var(--text-muted);">Rápido, seguro y sin contacto</p>
            </div>
        </div>
    </div>

    <!-- 3. CATEGORÍAS POPULARES -->
    <div style="margin-bottom: 3.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
            <div>
                <h2 style="font-size: 1.75rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;">Rubros y Departamentos</h2>
                <p style="margin: 0.3rem 0 0 0; color: var(--text-muted); font-size: 0.95rem;">Explora los productos organizados por categoría</p>
            </div>
            <a href="{{ route('tienda.catalogo') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.92rem;">
                Ver todas <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem;">
            @foreach($categorias as $cat)
                <a href="{{ route('tienda.catalogo', ['categoria' => $cat->slug]) }}" class="card" style="padding: 1.5rem 1rem; text-align: center; text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem; border-radius: 0.85rem;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 1.45rem; color: #E4007C; box-shadow: 0 4px 14px rgba(43,122,56,0.12); transition: box-shadow 0.3s, transform 0.3s cubic-bezier(0.34,1.56,0.64,1);" class="cat-icon-circle">
                        <i class="fa-solid fa-{{ $cat->icono ?? 'tag' }}"></i>
                    </div>
                    <span style="font-weight: 700; font-size: 0.92rem;">{{ $cat->nombre }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- 4. OFERTAS RELÁMPAGO CON CRONÓMETRO -->
    <div class="card" style="padding: 1.5rem 2rem; margin-bottom: 3.5rem; background: #FFFFFF; border-left: 5px solid var(--secondary); border-radius: 0.85rem; overflow: hidden; position: relative;">
        <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; border-radius: 50%; background: rgba(228,0,124,0.06); pointer-events: none;"></div>
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="background: linear-gradient(135deg, rgba(228,0,124,0.12) 0%, rgba(228,0,124,0.05) 100%); color: var(--secondary); padding: 0.4rem 0.9rem; border-radius: 9999px; font-weight: 800; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.4rem; border: 1px solid rgba(228,0,124,0.2); animation: badgePulseSale 2s ease-in-out infinite;">
                    <i class="fa-solid fa-fire" style="color: var(--secondary) !important;"></i> OFERTAS RELÁMPAGO
                </span>
                <span style="font-size: 0.95rem; font-weight: 700; color: var(--text-main);">Precios especiales con descuento inmediato</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 0.85rem; color: var(--text-muted);">Termina en:</span>
                <div style="background: #0F172A; color: white; padding: 0.35rem 0.85rem; border-radius: 0.4rem; font-weight: 800; font-size: 0.9rem; font-family: monospace; letter-spacing: 0.05em;" id="cronometro">
                    03:24:58
                </div>
            </div>
        </div>
    </div>

    <!-- 5. PRODUCTOS DESTACADOS DEL CATÁLOGO -->
    <div id="destacados" style="margin-bottom: 3.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem;">
            <div>
                <h2 style="font-size: 1.75rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;">Productos Destacados</h2>
                <p style="margin: 0.3rem 0 0 0; color: var(--text-muted); font-size: 0.95rem;">Selección prémium con disponibilidad inmediata en inventario</p>
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('tienda.catalogo.pdf') }}" class="btn-outline" style="border-radius: 9999px; padding: 0.45rem 1rem; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none;">
                    <i class="fa-solid fa-file-pdf" style="color: var(--secondary);"></i> Descargar Catálogo PDF
                </a>
                <a href="{{ route('tienda.catalogo') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.92rem;">
                    Ver Todo el Catálogo <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.75rem;">
            @foreach($destacados as $prod)
                <div class="card" style="display: flex; flex-direction: column; border-radius: 0.75rem;">
                    <div style="background: white; height: 220px; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; border-bottom: 1px solid var(--border-color); overflow: hidden;">
                        @if($prod->imagen_principal)
                            <img src="{{ $prod->imagen_principal }}" alt="{{ $prod->nombre }}"
                                style="width: 100%; height: 100%; object-fit: contain; padding: 1rem; transition: transform 0.45s cubic-bezier(0.16,1,0.3,1);"
                                onmouseover="this.style.transform='scale(1.07)'"
                                onmouseout="this.style.transform='scale(1)'"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display:none; width: 80px; height: 80px; border-radius: 50%; background: #FCE4EC; align-items: center; justify-content: center; box-shadow: 0 6px 16px rgba(228,0,124,0.15);">
                                <i class="fa-solid fa-mortar-pestle" style="font-size: 2.5rem; color: #E4007C;"></i>
                            </div>
                        @else
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: #FCE4EC; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 16px rgba(228,0,124,0.15);">
                                <i class="fa-solid fa-{{ $prod->categoria->icono ?? 'mortar-pestle' }}" style="font-size: 2.5rem; color: #E4007C;"></i>
                            </div>
                            <span style="font-size: 0.72rem; color: #52796F; font-weight: 700; margin-top: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em;">
                                {{ $prod->marca->nombre ?? 'Farmaluz' }}
                            </span>
                        @endif
                        @if($prod->tieneOfertaActiva())
                            <span style="position: absolute; top: 0.75rem; right: 0.75rem; background: var(--secondary); color: white; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; z-index: 2;">
                                OFERTA
                            </span>
                        @endif
                    </div>
                    <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
                        <span style="font-size: 0.78rem; font-weight: 600; text-transform: uppercase; color: var(--primary); margin-bottom: 0.4rem;">
                            {{ $prod->categoria->nombre ?? 'General' }}
                        </span>
                        <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0 0 0.5rem 0; line-height: 1.4;">
                            <a href="{{ route('tienda.producto', $prod->slug) }}" style="color: inherit; text-decoration: none;">
                                {{ $prod->nombre }}
                            </a>
                        </h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.25rem; flex: 1;">
                            {{ Str::limit($prod->descripcion_corta, 80) }}
                        </p>
                        
                        <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 1rem;">
                            <div>
                                @if($prod->tieneOfertaActiva())
                                    <span style="font-size: 1.35rem; font-weight: 800; color: #EF4444;">Bs. {{ number_format($prod->precio_oferta, 2) }}</span>
                                    <span style="font-size: 0.88rem; color: #94A3B8; text-decoration: line-through; margin-left: 0.4rem;">Bs. {{ number_format($prod->precio_venta, 2) }}</span>
                                @else
                                    <span style="font-size: 1.35rem; font-weight: 800; color: #0F172A;">Bs. {{ number_format($prod->precio_venta, 2) }}</span>
                                @endif
                            </div>
                            <span style="font-size: 0.8rem; color: #059669; font-weight: 600;">
                                <i class="fa-solid fa-check"></i> Stock: {{ $prod->stock_actual }}
                            </span>
                        </div>

                        <form action="{{ route('carrito.agregar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $prod->id }}">
                            <input type="hidden" name="cantidad" value="1">
                            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; border-radius: 0.5rem;">
                                <i class="fa-solid fa-cart-plus"></i> Agregar al Carrito
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<script>
    // Cuenta regresiva visual dinámica para Ofertas Relámpago
    function iniciarCronometro() {
        let horas = 3, minutos = 24, segundos = 58;
        const elem = document.getElementById('cronometro');
        if (!elem) return;

        setInterval(() => {
            segundos--;
            if (segundos < 0) {
                segundos = 59;
                minutos--;
                if (minutos < 0) {
                    minutos = 59;
                    horas--;
                }
            }
            elem.innerText = 
                String(horas).padStart(2, '0') + ':' + 
                String(minutos).padStart(2, '0') + ':' + 
                String(segundos).padStart(2, '0');
        }, 1000);
    }
    document.addEventListener('DOMContentLoaded', iniciarCronometro);
</script>
@endsection
