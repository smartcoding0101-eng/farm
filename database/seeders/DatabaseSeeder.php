<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tienda;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ZonaEnvio;
use App\Models\Cupon;
use App\Models\Configuracion;
use App\Models\MovimientoInventario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with Farmaluz natural pharmacy data.
     */
    public function run(): void
    {
        // 1. Tienda Farmaluz
        $tienda = Tienda::firstOrCreate(
            ['slug' => 'farmaluz-magistrales'],
            [
                'nombre' => 'FARMALUZ - Preparados Magistrales',
                'telefono' => '+591 70012345',
                'email' => 'contacto@farmaluz.bo',
                'moneda' => 'BOB',
                'activo' => true,
            ]
        );

        // 2. Usuario Administrador y Farmacéutico
        $admin = User::firstOrCreate(
            ['email' => 'admin@tienda.test'],
            [
                'name' => 'Dra. Luz Morales - Regente Farmacéutico',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'telefono' => '+591 70012345',
                'activo' => true,
            ]
        );

        $cliente = User::firstOrCreate(
            ['email' => 'cliente@tienda.test'],
            [
                'name' => 'María René Fernández',
                'password' => Hash::make('cliente12345'),
                'role' => 'cliente',
                'telefono' => '+591 76543210',
                'activo' => true,
            ]
        );

        // Asociar a la tienda Farmaluz
        if (!$admin->tiendas()->where('tiendas.id', $tienda->id)->exists()) {
            $admin->tiendas()->attach($tienda->id, ['rol' => 'dueno', 'es_predeterminada' => true]);
        }

        // Establecer tienda en TenantManager para que el seeder aplique tienda_id automáticamente
        $tenantMgr = app(\App\Services\TenantManager::class);
        $tenantMgr->setTienda($tienda);

        // 3. Configuraciones Farmaluz
        $configs = [
            'nombre_tienda' => 'FARMALUZ - Preparados Magistrales',
            'slogan' => 'Farmacia Natural, Fitoterapia y Fórmulas Magistrales Personalizadas',
            'moneda' => 'BOB',
            'simbolo_moneda' => 'Bs.',
            'telefono_whatsapp' => '59170012345',
            'mensaje_whatsapp_default' => '¡Hola Farmaluz! Deseo consultar sobre preparados magistrales o medicina natural: ',
            'email_contacto' => 'contacto@farmaluz.bo',
            'direccion_tienda' => 'Av. Arce esq. Cordero, Edificio Platinum Mezzanine 2, La Paz - Bolivia',
            'horario_atencion' => 'Lunes a Viernes de 08:30 a 19:30 | Sábados de 09:00 a 14:00',
            'qr_banco' => 'Banco Nacional de Bolivia / BCP',
            'qr_titular' => 'FARMALUZ LABORATORIOS S.R.L.',
            'qr_imagen' => '/images/farmaluz-logo.png',
            'habilitar_qr' => '1',
            'habilitar_transferencia' => '1',
            'habilitar_contraentrega' => '1',
        ];

        foreach ($configs as $clave => $valor) {
            Configuracion::set($clave, $valor, 'general');
        }

        // 4. Zonas de Envío Farmaluz con Cadena de Frío y Entregas Urgentes
        $zonas = [
            [
                'nombre' => 'La Paz (Centro, Miraflores, Sopocachi)',
                'departamento' => 'La Paz',
                'costo_envio' => 15.00,
                'monto_minimo_envio_gratis' => 180.00,
                'tiempo_estimado' => 'Mismo día (2 a 4 horas)'
            ],
            [
                'nombre' => 'Zona Sur (Calacoto, San Miguel, Achumani, Obrajes)',
                'departamento' => 'La Paz',
                'costo_envio' => 20.00,
                'monto_minimo_envio_gratis' => 220.00,
                'tiempo_estimado' => 'Mismo día (3 a 5 horas)'
            ],
            [
                'nombre' => 'El Alto (Ceja, Satélite, Villa Adela)',
                'departamento' => 'La Paz',
                'costo_envio' => 20.00,
                'monto_minimo_envio_gratis' => 220.00,
                'tiempo_estimado' => '24 horas'
            ],
            [
                'nombre' => 'Envíos Nacionales Especializados (Cochabamba, Santa Cruz, Sucre)',
                'departamento' => 'Nacional',
                'costo_envio' => 35.00,
                'monto_minimo_envio_gratis' => 350.00,
                'tiempo_estimado' => '24 a 48 horas con embalaje térmico protector'
            ],
        ];

        foreach ($zonas as $zona) {
            ZonaEnvio::firstOrCreate(['nombre' => $zona['nombre']], $zona);
        }

        // 5. Cupones de Demostración Farmaluz
        Cupon::firstOrCreate(
            ['codigo' => 'SALUDNATURAL'],
            [
                'tipo' => 'porcentaje',
                'valor' => 10.00,
                'monto_minimo' => 80.00,
                'limite_usos' => 200,
                'fecha_inicio' => now()->subDay(),
                'fecha_fin' => now()->addMonths(6),
                'activo' => true,
            ]
        );

        Cupon::firstOrCreate(
            ['codigo' => 'MAGISTRAL30'],
            [
                'tipo' => 'fijo',
                'valor' => 30.00,
                'monto_minimo' => 250.00,
                'limite_usos' => 100,
                'fecha_inicio' => now()->subDay(),
                'fecha_fin' => now()->addMonths(3),
                'activo' => true,
            ]
        );

        // 6. Categorías de Farmacia Natural & Preparados Magistrales
        $catData = [
            [
                'nombre' => 'Fitoterapia & Tinturas Madre',
                'icono' => 'seedling',
                'descripcion' => 'Extractos hidroalcohólicos y gotas naturales estandarizadas de pasiflora, valeriana, propóleo y alcachofa.',
                'destacada' => true,
            ],
            [
                'nombre' => 'Preparados Magistrales',
                'icono' => 'mortar-pestle',
                'descripcion' => 'Fórmulas individualizadas, cápsulas botánicas, ungüentos galénicos y emulsiones elaboradas bajo receta farmacéutica.',
                'destacada' => true,
            ],
            [
                'nombre' => 'Dermofarmacia & Cuidado de la Piel',
                'icono' => 'spa',
                'descripcion' => 'Cremas nutritivas con caléndula, árnica montana, geles puros de aloe vera y serums antiedad botánicos.',
                'destacada' => true,
            ],
            [
                'nombre' => 'Aceites Esenciales Puros',
                'icono' => 'droplet',
                'descripcion' => 'Aceites 100% puros y quimiotipados de lavanda francesa, árbol de té, eucalipto glóbulus y menta piperita.',
                'destacada' => true,
            ],
            [
                'nombre' => 'Suplementos & Antioxidantes',
                'icono' => 'capsules',
                'descripcion' => 'Citrato de magnesio puro, colágeno hidrolizado marino, espirulina orgánica, cúrcuma y zinc biodisponible.',
                'destacada' => true,
            ],
            [
                'nombre' => 'Jarabes & Vías Respiratorias',
                'icono' => 'bottle-droplet',
                'descripcion' => 'Jarabes bronquiales a base de miel de abeja virgen, propóleo verde, eucalipto, wira wira y tomillo.',
                'destacada' => false,
            ],
        ];

        $categorias = [];
        foreach ($catData as $item) {
            $cat = Categoria::firstOrCreate(
                ['slug' => Str::slug($item['nombre'])],
                [
                    'tienda_id' => $tienda->id,
                    'nombre' => $item['nombre'],
                    'icono' => $item['icono'],
                    'descripcion' => $item['descripcion'],
                    'destacada' => $item['destacada'],
                    'activo' => true,
                ]
            );
            $categorias[$item['nombre']] = $cat;
        }

        // 7. Marcas y Líneas de Laboratorio Botánico
        $marcasData = [
            'Farmaluz Magistral' => 'Fórmulas exclusivas y preparados galénicos elaborados en nuestro laboratorio farmacéutico.',
            'Botánica Andina' => 'Extractos y tinturas de hierbas medicinales nativas con certificación ecológica.',
            'PhytoVital' => 'Suplementos nutricionales y oligoelementos con alta biodisponibilidad celular.',
            'DermoNatura' => 'Dermofarmacia hipoalergénica con aceites vegetales prensados en frío.',
            'AromaLab Pura' => 'Aceites esenciales de grado terapéutico y destilación por arrastre de vapor.',
        ];

        $marcas = [];
        foreach ($marcasData as $nombre => $desc) {
            $marca = Marca::firstOrCreate(
                ['slug' => Str::slug($nombre)],
                [
                    'tienda_id' => $tienda->id,
                    'nombre' => $nombre,
                    'descripcion' => $desc,
                    'activo' => true,
                ]
            );
            $marcas[$nombre] = $marca;
        }

        // 8. Catálogo Completo de Productos de Farmacia Natural
        $productosData = [
            // Fitoterapia
            [
                'categoria' => 'Fitoterapia & Tinturas Madre',
                'marca' => 'Farmaluz Magistral',
                'nombre' => 'Tintura Madre Valeriana & Pasiflora (Relax Nocturno 50ml)',
                'sku' => 'FIT-VAL-PAS-50ML',
                'precio_venta' => 65.00,
                'costo_adquisicion' => 32.00,
                'precio_oferta' => 55.00,
                'stock_actual' => 35,
                'destacado' => true,
                'descripcion_corta' => 'Extracto hidroalcohólico concentrado para conciliar el sueño profundo y aliviar la ansiedad.',
                'descripcion_completa' => 'Fórmula botánica con extracto fluido de raíz de Valeriana officinalis y flores de Passiflora incarnata. Promueve la relajación del sistema nervioso sin generar dependencia ni somnolencia residual diurna.',
            ],
            [
                'categoria' => 'Fitoterapia & Tinturas Madre',
                'marca' => 'Botánica Andina',
                'nombre' => 'Gotas Digestivas de Boldo, Carqueja & Menta (Digest-Luz 60ml)',
                'sku' => 'FIT-DIG-BOL-60ML',
                'precio_venta' => 58.00,
                'costo_adquisicion' => 28.00,
                'precio_oferta' => null,
                'stock_actual' => 40,
                'destacado' => true,
                'descripcion_corta' => 'Regulador hepático y digestivo natural, alivia pesadez y cólicos estomacales.',
                'descripcion_completa' => 'Combinación magistral de hierbas amargas hepatoprotectoras. Estimula la producción biliar, optimiza la asimilación de alimentos grasos y previene la hinchazón abdominal tras las comidas.',
            ],

            // Preparados Magistrales
            [
                'categoria' => 'Preparados Magistrales',
                'marca' => 'Farmaluz Magistral',
                'nombre' => 'Ungüento Magistral de Árnica, Harpagofito & Sauce 100g',
                'sku' => 'MAG-ARN-HAR-100G',
                'precio_venta' => 75.00,
                'costo_adquisicion' => 38.00,
                'precio_oferta' => 65.00,
                'stock_actual' => 25,
                'destacado' => true,
                'descripcion_corta' => 'Antiinflamatorio y analgésico tópico galénico para dolores articulares y musculares.',
                'descripcion_completa' => 'Pomada elaborada artesanalmente con extracto de Árnica montana al 15%, corteza de sauce blanco (salicina natural) y raíz de harpagofito. Ideal para contracturas, artrosis, esguinces y fatiga muscular.',
            ],
            [
                'categoria' => 'Preparados Magistrales',
                'marca' => 'Farmaluz Magistral',
                'nombre' => 'Cápsulas Magistrales de Cardo Mariano & Alcachofa (60 Cápsulas)',
                'sku' => 'MAG-CAR-ALC-60CAP',
                'precio_venta' => 110.00,
                'costo_adquisicion' => 60.00,
                'precio_oferta' => null,
                'stock_actual' => 30,
                'destacado' => true,
                'descripcion_corta' => 'Desintoxicante hepático intensivo con Silimarina estandarizada al 80%.',
                'descripcion_completa' => 'Cápsulas vegetales compuestas por extracto seco estandarizado de Silybum marianum (Silimarina) y Cynara scolymus. Protege los hepatocitos contra toxinas, coadyuva en hígado graso y favorece la digestión.',
            ],

            // Dermofarmacia
            [
                'categoria' => 'Dermofarmacia & Cuidado de la Piel',
                'marca' => 'DermoNatura',
                'nombre' => 'Crema Facial Regeneradora de Caléndula & Manzanilla 100g',
                'sku' => 'DER-CAL-MAN-100G',
                'precio_venta' => 85.00,
                'costo_adquisicion' => 42.00,
                'precio_oferta' => 75.00,
                'stock_actual' => 28,
                'destacado' => true,
                'descripcion_corta' => 'Crema calmante y cicatrizante para pieles sensibles, rosácea o irritadas.',
                'descripcion_completa' => 'Base hipoalergénica no comedogénica enriquecida con oleato de caléndula officinales, bisabolol natural de manzanilla y manteca de karité pura. Calma el enrojecimiento, repara la barrera cutánea y restaura la hidratación profunda.',
            ],
            [
                'categoria' => 'Dermofarmacia & Cuidado de la Piel',
                'marca' => 'DermoNatura',
                'nombre' => 'Serum Botánico Antiedad con Ácido Hialurónico Vegetal & Rosa Mosqueta 30ml',
                'sku' => 'DER-SER-ROS-30ML',
                'precio_venta' => 125.00,
                'costo_adquisicion' => 65.00,
                'precio_oferta' => 110.00,
                'stock_actual' => 20,
                'destacado' => true,
                'descripcion_corta' => 'Fórmula sedosa reafirmante con antioxidantes botánicos y aceite virgen de rosa mosqueta.',
                'descripcion_completa' => 'Serum intensivo libre de parabenos y siliconas. Penetra capas epidérmicas profundas para estimular la síntesis de colágeno, atenuar líneas de expresión y unificar el tono de la piel.',
            ],

            // Aceites Esenciales
            [
                'categoria' => 'Aceites Esenciales Puros',
                'marca' => 'AromaLab Pura',
                'nombre' => 'Aceite Esencial Puro de Lavanda Francesa Grado Terapéutico 15ml',
                'sku' => 'ACE-LAV-FRA-15ML',
                'precio_venta' => 70.00,
                'costo_adquisicion' => 35.00,
                'precio_oferta' => null,
                'stock_actual' => 45,
                'destacado' => true,
                'descripcion_corta' => 'Aceite 100% puro para difusor, masajes relajantes y aromaterapia de paz mental.',
                'descripcion_completa' => 'Destilado al vapor de sumidades floridas de Lavandula angustifolia. Reconocido por sus propiedades ansiolíticas, antisépticas leves y su aroma reconfortante para dormitorios y espacios de descanso.',
            ],
            [
                'categoria' => 'Aceites Esenciales Puros',
                'marca' => 'AromaLab Pura',
                'nombre' => 'Aceite Esencial de Árbol de Té Australiano (Tea Tree) 15ml',
                'sku' => 'ACE-TEA-TRE-15ML',
                'precio_venta' => 68.00,
                'costo_adquisicion' => 34.00,
                'precio_oferta' => 59.00,
                'stock_actual' => 38,
                'destacado' => false,
                'descripcion_corta' => 'Potente antibacteriano, antifúngico y purificante natural para cutis graso y uñas.',
                'descripcion_completa' => 'Melaleuca alternifolia 100% pura y sin diluir. Indicado para tratar brotes de acné, hongos en uñas (onicomicosis), picaduras de insectos y desinfección ambiental en vaporizadores.',
            ],

            // Suplementos
            [
                'categoria' => 'Suplementos & Antioxidantes',
                'marca' => 'PhytoVital',
                'nombre' => 'Citrato de Magnesio Puro en Polvo 250g (Grado USP Alta Absorción)',
                'sku' => 'SUP-MAG-CIT-250G',
                'precio_venta' => 95.00,
                'costo_adquisicion' => 50.00,
                'precio_oferta' => 85.00,
                'stock_actual' => 50,
                'destacado' => true,
                'descripcion_corta' => 'Mineral biodisponible para aliviar calambres, fatiga, mejorar el descanso y la función muscular.',
                'descripcion_completa' => 'Magnesio enlazado a ácido cítrico para una tasa de absorción superior al 90%, evitando malestares gástricos. Rinde más de 80 tomas diarias. Libre de aditivos, saborizantes o azúcar añadida.',
            ],
            [
                'categoria' => 'Suplementos & Antioxidantes',
                'marca' => 'PhytoVital',
                'nombre' => 'Propóleo Verde Titulado al 30% en Gotas 30ml',
                'sku' => 'SUP-PRO-VER-30ML',
                'precio_venta' => 50.00,
                'costo_adquisicion' => 24.00,
                'precio_oferta' => null,
                'stock_actual' => 60,
                'destacado' => false,
                'descripcion_corta' => 'Escudo inmunológico natural con bioflavonoides y Artepillina-C antiviral.',
                'descripcion_completa' => 'Extracto concentrado de propóleo virgen de abejas con alta concentración de compuestos fenólicos. Refuerza las defensas naturales, protege la garganta y desinfecta las vías bucofaríngeas.',
            ],

            // Jarabes
            [
                'categoria' => 'Jarabes & Vías Respiratorias',
                'marca' => 'Botánica Andina',
                'nombre' => 'Jarabe Balsámico de Miel, Propóleo, Eucalipto & Wira Wira 200ml',
                'sku' => 'JAR-BAL-WIR-200ML',
                'precio_venta' => 48.00,
                'costo_adquisicion' => 22.00,
                'precio_oferta' => 42.00,
                'stock_actual' => 45,
                'destacado' => true,
                'descripcion_corta' => 'Expectorante y calmante bronquial con hierbas andinas tradicionales y miel pura.',
                'descripcion_completa' => 'Jarabe tradicional con infusión concentrada de Wira Wira (Gnaphalium), hojas tiernas de eucalipto, tomillo y miel pura de valles bolivianos. Suaviza la garganta irritada y facilita la expulsión de flemas.',
            ],
        ];

        foreach ($productosData as $p) {
            $cat = $categorias[$p['categoria']];
            $marca = $marcas[$p['marca']];

            $producto = Producto::create([
                'tienda_id' => $tienda->id,
                'categoria_id' => $cat->id,
                'marca_id' => $marca->id,
                'nombre' => $p['nombre'],
                'slug' => Str::slug($p['nombre']),
                'sku' => $p['sku'],
                'descripcion_corta' => $p['descripcion_corta'],
                'descripcion_completa' => $p['descripcion_completa'],
                'precio_venta' => $p['precio_venta'],
                'costo_adquisicion' => $p['costo_adquisicion'],
                'precio_oferta' => $p['precio_oferta'],
                'stock_actual' => $p['stock_actual'],
                'stock_minimo' => 5,
                'destacado' => $p['destacado'],
                'nuevo' => true,
                'estado' => 'publicado',
            ]);

            // Kardex Inicial de Farmaluz
            MovimientoInventario::create([
                'tienda_id' => $tienda->id,
                'producto_id' => $producto->id,
                'tipo' => 'entrada',
                'cantidad' => $p['stock_actual'],
                'user_id' => $admin->id,
                'stock_anterior' => 0,
                'stock_posterior' => $p['stock_actual'],
                'motivo' => 'Inventario inicial Farmaluz Preparados Magistrales',
            ]);
        }
    }
}
