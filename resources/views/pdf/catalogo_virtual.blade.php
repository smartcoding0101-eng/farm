<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo Oficial de Productos - Farmaluz</title>
    <style>
        @page {
            margin: 0.8cm 1cm 0.9cm 1cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #132A17;
            background: #FFFFFF;
            margin: 0;
            padding: 0;
            font-size: 8.5pt;
            line-height: 1.25;
        }

        /* ENCABEZADO SUPERIOR */
        .hdr-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2pt solid #2B7A38;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .hdr-logo {
            width: 50px;
            height: auto;
            vertical-align: middle;
        }

        .hdr-title {
            font-size: 16pt;
            font-weight: bold;
            color: #2B7A38;
            margin: 0;
            line-height: 1.1;
            letter-spacing: 0.5px;
        }

        .hdr-sub {
            font-size: 7.5pt;
            font-weight: bold;
            color: #E4007C;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin: 1px 0 0 0;
        }

        .hdr-meta {
            text-align: right;
            vertical-align: middle;
            font-size: 7.2pt;
            color: #4A6352;
            line-height: 1.3;
        }

        .badge-doc {
            display: inline-block;
            background: #E4007C;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 6.8pt;
            padding: 2px 6px;
            border-radius: 2px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        /* BARRA RESUMIDA */
        .strip-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #F4FAF5;
            border: 1px solid #D6E8D9;
            margin-bottom: 8px;
        }

        .strip-table td {
            padding: 4px 8px;
            font-size: 7pt;
            color: #2D4A34;
        }

        /* TABLA PRINCIPAL DE PRODUCTOS: ESPACIOS INTERIORES AMPLIOS Y CONFORTABLES */
        .catalog-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cat-row-hdr td {
            background-color: #2B7A38;
            color: #FFFFFF;
            padding: 8px 12px;
            font-size: 9.5pt;
            font-weight: bold;
            border-top: 1.5px solid #1E5C29;
            border-bottom: 1.5px solid #1E5C29;
        }

        .cat-desc-cell {
            text-align: right;
            font-size: 7.5pt;
            font-weight: normal;
            color: #E8F5E9;
        }

        .item-row td {
            padding: 10px 12px;
            border-bottom: 1px solid #E2ECE3;
            vertical-align: middle;
        }

        .item-row:nth-child(even) td {
            background-color: #FAFCFA;
        }

        .col-thumb {
            width: 64px;
            text-align: center;
            padding-right: 6px !important;
        }

        .thumb-img {
            max-width: 58px;
            max-height: 58px;
            display: block;
            margin: 0 auto;
            border-radius: 4px;
            border: 1px solid #DFEBE0;
            background: #FFFFFF;
            padding: 2px;
        }

        .thumb-fallback {
            width: 52px;
            height: 52px;
            line-height: 52px;
            background: #FCE4EC;
            color: #E4007C;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            border-radius: 4px;
            margin: 0 auto;
        }

        .col-info {
            vertical-align: middle !important;
            padding-left: 8px !important;
            padding-right: 14px !important;
        }

        .item-brand {
            font-size: 7pt;
            font-weight: bold;
            color: #E4007C;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: inline-block;
            margin-bottom: 2px;
        }

        .item-name {
            font-size: 9.2pt;
            font-weight: bold;
            color: #132A17;
            margin: 2px 0 4px 0;
            line-height: 1.22;
        }

        .item-desc {
            font-size: 7.5pt;
            color: #4A6352;
            line-height: 1.35;
            margin: 0;
        }

        .col-sku {
            width: 125px;
            font-size: 7.3pt;
            color: #55695B;
            vertical-align: middle !important;
            line-height: 1.45;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        .stock-tag {
            color: #2B7A38;
            font-weight: bold;
            font-size: 7.3pt;
        }

        .col-price {
            width: 110px;
            text-align: right;
            vertical-align: middle !important;
            padding-left: 8px !important;
        }

        .price-val {
            font-size: 11pt;
            font-weight: bold;
            color: #132A17;
            display: block;
        }

        .price-val.offer {
            color: #E4007C;
        }

        .price-old {
            font-size: 7.5pt;
            color: #94A3B8;
            text-decoration: line-through;
            display: block;
            margin-bottom: 1px;
        }

        .offer-pill {
            background-color: #E4007C;
            color: #FFFFFF;
            font-size: 6.2pt;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 3px;
            text-transform: uppercase;
            display: inline-block;
            margin-left: 5px;
            vertical-align: 1px;
        }

        /* PIE DE PÁGINA */
        .ftr-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1.5pt solid #2B7A38;
            margin-top: 12px;
            padding-top: 6px;
            font-size: 7.2pt;
            color: #4A6352;
        }
    </style>
</head>
<body>

    <!-- ENCABEZADO -->
    <table class="hdr-table">
        <tr>
            <td style="width: 55px; vertical-align: middle;">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" class="hdr-logo" alt="Logo">
                @endif
            </td>
            <td style="vertical-align: middle; padding-left: 4px;">
                <h1 class="hdr-title">FARMALUZ</h1>
                <p class="hdr-sub">Preparados Magistrales & Farmacia Natural</p>
            </td>
            <td class="hdr-meta">
                <span class="badge-doc">Catálogo de Productos</span><br>
                <strong>WhatsApp / Pedidos:</strong> {{ $telefono }}<br>
                <strong>Dirección:</strong> {{ $direccion }}
            </td>
        </tr>
    </table>

    <!-- BARRA GUÍA -->
    <table class="strip-table">
        <tr>
            <td style="width: 33%;">
                <strong style="color: #2B7A38;">✔ Calidad Galénica:</strong> Fórmulas con extractos puros y registro botánico.
            </td>
            <td style="width: 33%; border-left: 1px solid #D6E8D9;">
                <strong style="color: #E4007C;">★ Pedidos por WhatsApp:</strong> Envío inmediato con pago QR bancario a toda Bolivia.
            </td>
            <td style="width: 34%; border-left: 1px solid #D6E8D9;">
                <strong style="color: #2B7A38;">✉ Contacto:</strong> {{ $email }} | <strong>Fecha:</strong> {{ $fechaEmision }}
            </td>
        </tr>
    </table>

    <!-- TABLA COMPACTA CONTINUA (SIN VACÍOS NI HUECOS) -->
    <table class="catalog-table">
        @foreach($categorias as $cat)
            <!-- TÍTULO DE LA CATEGORÍA -->
            <tr class="cat-row-hdr">
                <td colspan="4">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="border: none; padding: 0; color: #FFF; font-weight: bold; font-size: 8.8pt;">
                                {{ $cat->nombre }}
                            </td>
                            <td class="cat-desc-cell" style="border: none; padding: 0;">
                                {{ $cat->descripcion ?? 'Línea botánica certificada' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- PRODUCTOS DE ESTA CATEGORÍA -->
            @foreach($cat->productos as $prod)
                <tr class="item-row">
                    <!-- FOTO -->
                    <td class="col-thumb">
                        @if(!empty($prod->img_src))
                            <img src="{{ $prod->img_src }}" class="thumb-img" alt="{{ $prod->nombre }}">
                        @else
                            <div class="thumb-fallback">LUZ</div>
                        @endif
                    </td>

                    <!-- NOMBRE Y DESCRIPCIÓN -->
                    <td class="col-info">
                        <span class="item-brand">{{ $prod->marca->nombre ?? 'Farmaluz Magistral' }}</span>
                        @if($prod->tieneOfertaActiva())
                            <span class="offer-pill">OFERTA</span>
                        @endif
                        <div class="item-name">{{ $prod->nombre }}</div>
                        <p class="item-desc">{{ $prod->descripcion_corta ?? Str::limit($prod->descripcion_completa, 100) }}</p>
                    </td>

                    <!-- SKU Y STOCK -->
                    <td class="col-sku">
                        <strong>SKU:</strong> {{ $prod->sku }}<br>
                        <span class="stock-tag">✔ Disp: {{ $prod->stock_actual }} u.</span>
                    </td>

                    <!-- PRECIO -->
                    <td class="col-price">
                        @if($prod->tieneOfertaActiva())
                            <span class="price-old">Bs. {{ number_format($prod->precio_venta, 2) }}</span>
                            <span class="price-val offer">Bs. {{ number_format($prod->precio_oferta, 2) }}</span>
                        @else
                            <span class="price-val">Bs. {{ number_format($prod->precio_venta, 2) }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endforeach
    </table>

    <!-- PIE DE PÁGINA -->
    <table class="ftr-table">
        <tr>
            <td style="width: 70%;">
                <strong>FARMALUZ - LABORATORIO GALÉNICO & MEDICINA NATURAL</strong> | {{ $direccion }}<br>
                <span>Precios y stock sujetos a disponibilidad de inventario. Envíos nacionales mediante transporte seguro.</span>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: bottom;">
                <strong>Documento Oficial</strong> | {{ date('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

</body>
</html>
