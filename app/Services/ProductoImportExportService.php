<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\MovimientoInventario;
use App\Models\ImagenProducto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use ZipArchive;

class ProductoImportExportService
{
    /**
     * Genera la plantilla de Excel oficial para descarga con ejemplos y datos de referencia
     */
    public function generarPlantillaExcel(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productos Farmaluz');

        // Encabezados
        $headers = [
            'A1' => 'SKU (Opcional - Se autogenera)',
            'B1' => 'Nombre del Producto (*)',
            'C1' => 'Categoría (*)',
            'D1' => 'Laboratorio / Marca',
            'E1' => 'Precio Venta BOB (*)',
            'F1' => 'Costo Adquisición BOB',
            'G1' => 'Precio Oferta BOB',
            'H1' => 'Stock Inicial (*)',
            'I1' => 'Stock Mínimo',
            'J1' => 'Descripción Corta',
            'K1' => 'Descripción Completa',
            'L1' => 'Imagen Principal (URL o archivo.jpg)',
            'M1' => '¿Destacado? (SI / NO)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // Estilos para encabezados (Verde Farmaluz)
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Calibri',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2B7A38'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '1E5C29'],
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(32);

        // Filas de ejemplo de Farmacia Natural Farmaluz
        $ejemplos = [
            [
                '', // SKU vacío para autogenerar
                'Tintura Madre Pasiflora & Valeriana 60ml',
                'Fitoterapia & Tinturas Madre',
                'Farmaluz Magistral',
                65.00,
                32.00,
                55.00,
                30,
                5,
                'Gotas naturales para conciliar el sueño y aliviar el estrés.',
                'Extracto botánico hidroalcohólico concentrado sin generar dependencia.',
                'https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=600&q=80',
                'SI',
            ],
            [
                '',
                'Pomada Galénica de Árnica Montana & Sauce 100g',
                'Preparados Magistrales',
                'Farmaluz Magistral',
                75.00,
                38.00,
                65.00,
                25,
                5,
                'Alivio intensivo de contracturas musculares y golpes.',
                'Fórmula artesanal magistral con salicina natural y árnica pura.',
                'unguento_arnica.jpg',
                'SI',
            ],
            [
                '',
                'Serum Facial con Caléndula & Ácido Hialurónico 30ml',
                'Dermofarmacia & Cuidado de la Piel',
                'DermoNatura',
                120.00,
                60.00,
                105.00,
                20,
                3,
                'Serum calmante y regenerador para piel sensible.',
                'Hidratación profunda, atenúa líneas de expresión y rojeces.',
                'https://images.unsplash.com/photo-1608248597359-f8ff2b9a7be2?auto=format&fit=crop&w=600&q=80',
                'SI',
            ],
            [
                '',
                'Aceite Esencial de Eucalipto Glóbulus 15ml',
                'Aceites Esenciales Puros',
                'AromaLab Pura',
                55.00,
                26.00,
                '',
                40,
                5,
                'Descongestionante respiratorio y purificador ambiental.',
                'Destilación 100% pura por arrastre de vapor para difusores o inhalaciones.',
                'aceite_eucalipto.png',
                'NO',
            ],
            [
                '',
                'Citrato de Magnesio Grado USP 250g en Polvo',
                'Suplementos & Antioxidantes',
                'PhytoVital',
                95.00,
                50.00,
                85.00,
                50,
                8,
                'Alta biodisponibilidad para músculos, descanso y energía.',
                'Polvo puro sin azúcar añadida ni saborizantes artificiales.',
                '',
                'SI',
            ],
        ];

        $rowNum = 2;
        foreach ($ejemplos as $row) {
            $sheet->fromArray($row, null, 'A' . $rowNum);
            $sheet->getRowDimension($rowNum)->setRowHeight(24);
            $rowNum++;
        }

        // Bordes y alineación para datos de ejemplo
        $sheet->getStyle('A2:M' . ($rowNum - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E0EBE0'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Autoajustar anchos de columnas
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Segunda Hoja con Guía de Categorías y Marcas válidas en el sistema
        $refSheet = $spreadsheet->createSheet();
        $refSheet->setTitle('Guia de Referencia');

        $refSheet->setCellValue('A1', 'Categorías Registradas');
        $refSheet->setCellValue('B1', 'Laboratorios / Marcas Registradas');
        $refSheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E4007C']],
        ]);

        $categorias = Categoria::where('activo', true)->pluck('nombre')->toArray();
        $marcas = Marca::where('activo', true)->pluck('nombre')->toArray();

        $maxRows = max(count($categorias), count($marcas));
        for ($i = 0; $i < $maxRows; $i++) {
            if (isset($categorias[$i])) $refSheet->setCellValue('A' . ($i + 2), $categorias[$i]);
            if (isset($marcas[$i])) $refSheet->setCellValue('B' . ($i + 2), $marcas[$i]);
        }
        $refSheet->getColumnDimension('A')->setAutoSize(true);
        $refSheet->getColumnDimension('B')->setAutoSize(true);

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Procesa la importación masiva de productos desde el archivo Excel y opcional ZIP de imágenes
     */
    public function importarProductos($archivoExcel, $archivoZip = null, $userId = 1): array
    {
        $tenantMgr = app(TenantManager::class);
        $tienda = $tenantMgr->getTienda();
        $tiendaId = $tienda ? $tienda->id : null;

        // Si se subió ZIP, extraer imágenes en carpeta temporal
        $tempZipFolder = null;
        if ($archivoZip && $archivoZip->isValid()) {
            $zip = new ZipArchive();
            if ($zip->open($archivoZip->getRealPath()) === true) {
                $tempZipFolder = storage_path('app/temp_zip_' . uniqid());
                if (!file_exists($tempZipFolder)) {
                    mkdir($tempZipFolder, 0777, true);
                }
                $zip->extractTo($tempZipFolder);
                $zip->close();
            }
        }

        $spreadsheet = IOFactory::load($archivoExcel->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Eliminar fila de encabezados
        array_shift($rows);

        $creados = 0;
        $actualizados = 0;
        $errores = [];

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $filaNumero = $index + 2;

                $sku = trim((string)($row[0] ?? ''));
                $nombre = trim((string)($row[1] ?? ''));
                $categoriaNombre = trim((string)($row[2] ?? ''));
                $marcaNombre = trim((string)($row[3] ?? ''));
                $precioVenta = floatval($row[4] ?? 0);
                $costoAdquisicion = floatval($row[5] ?? 0);
                $precioOferta = !empty($row[6]) ? floatval($row[6]) : null;
                $stockInicial = intval($row[7] ?? 0);
                $stockMinimo = !empty($row[8]) ? intval($row[8]) : 5;
                $descripcionCorta = trim((string)($row[9] ?? ''));
                $descripcionCompleta = trim((string)($row[10] ?? ''));
                $imagenReferencia = trim((string)($row[11] ?? ''));
                $destacadoStr = strtoupper(trim((string)($row[12] ?? '')));
                $destacado = in_array($destacadoStr, ['SI', 'YES', '1', 'TRUE']);

                // Validar campos obligatorios
                if (empty($nombre)) {
                    continue; // Fila vacía
                }

                if ($precioVenta <= 0) {
                    $errores[] = "Fila #{$filaNumero} ('{$nombre}'): El precio de venta debe ser mayor a 0.";
                    continue;
                }

                if (empty($categoriaNombre)) {
                    $categoriaNombre = 'Fitoterapia & Tinturas Madre';
                }

                // Buscar o crear la categoría
                $categoria = Categoria::firstOrCreate(
                    [
                        'tienda_id' => $tiendaId,
                        'slug' => Str::slug($categoriaNombre)
                    ],
                    [
                        'nombre' => $categoriaNombre,
                        'icono' => 'leaf',
                        'activo' => true
                    ]
                );

                // Buscar o crear la marca/laboratorio
                $marcaId = null;
                if (!empty($marcaNombre)) {
                    $marca = Marca::firstOrCreate(
                        [
                            'tienda_id' => $tiendaId,
                            'slug' => Str::slug($marcaNombre)
                        ],
                        [
                            'nombre' => $marcaNombre,
                            'activo' => true
                        ]
                    );
                    $marcaId = $marca->id;
                }

                // Autogenerar código / SKU inteligente si viene vacío
                if (empty($sku)) {
                    $sku = $this->generarSkuAutomatico($categoriaNombre, $tiendaId);
                }

                // Procesamiento de Imagen (URL o archivo local)
                $rutaImagenPrincipal = null;
                if (!empty($imagenReferencia)) {
                    $rutaImagenPrincipal = $this->procesarImagen($imagenReferencia, $tempZipFolder, $sku);
                }

                // Buscar si ya existe por SKU dentro de la tienda
                $productoExistente = Producto::where('sku', $sku)->first();

                if ($productoExistente) {
                    // Actualizar producto
                    $productoExistente->update([
                        'nombre' => $nombre,
                        'categoria_id' => $categoria->id,
                        'marca_id' => $marcaId,
                        'precio_venta' => $precioVenta,
                        'costo_adquisicion' => $costoAdquisicion,
                        'precio_oferta' => $precioOferta,
                        'stock_minimo' => $stockMinimo,
                        'descripcion_corta' => $descripcionCorta,
                        'descripcion_completa' => $descripcionCompleta,
                        'destacado' => $destacado,
                        'imagen_principal' => $rutaImagenPrincipal ?? $productoExistente->imagen_principal,
                    ]);

                    // Si se indicó stock inicial y difiere del actual, generar movimiento kardex
                    if ($stockInicial != $productoExistente->stock_actual) {
                        $diferencia = $stockInicial - $productoExistente->stock_actual;
                        $tipo = $diferencia > 0 ? 'entrada' : 'ajuste';

                        MovimientoInventario::create([
                            'tienda_id' => $tiendaId,
                            'producto_id' => $productoExistente->id,
                            'user_id' => $userId,
                            'tipo' => $tipo,
                            'cantidad' => abs($diferencia),
                            'stock_anterior' => $productoExistente->stock_actual,
                            'stock_posterior' => $stockInicial,
                            'motivo' => 'Ajuste por carga masiva Excel',
                        ]);

                        $productoExistente->update(['stock_actual' => $stockInicial]);
                    }

                    $actualizados++;
                } else {
                    // Crear nuevo producto
                    $nuevoProd = Producto::create([
                        'tienda_id' => $tiendaId,
                        'categoria_id' => $categoria->id,
                        'marca_id' => $marcaId,
                        'sku' => $sku,
                        'nombre' => $nombre,
                        'slug' => Str::slug($nombre) . '-' . Str::lower(Str::random(4)),
                        'precio_venta' => $precioVenta,
                        'costo_adquisicion' => $costoAdquisicion,
                        'precio_oferta' => $precioOferta,
                        'stock_actual' => $stockInicial,
                        'stock_minimo' => $stockMinimo,
                        'descripcion_corta' => $descripcionCorta,
                        'descripcion_completa' => $descripcionCompleta,
                        'destacado' => $destacado,
                        'imagen_principal' => $rutaImagenPrincipal,
                        'estado' => 'publicado',
                    ]);

                    if ($stockInicial > 0) {
                        MovimientoInventario::create([
                            'tienda_id' => $tiendaId,
                            'producto_id' => $nuevoProd->id,
                            'user_id' => $userId,
                            'tipo' => 'entrada',
                            'cantidad' => $stockInicial,
                            'stock_anterior' => 0,
                            'stock_posterior' => $stockInicial,
                            'motivo' => 'Inventario inicial desde carga masiva Excel',
                        ]);
                    }

                    $creados++;
                }
            }

            DB::commit();

            // Limpiar carpeta temporal de zip si existió
            if ($tempZipFolder && file_exists($tempZipFolder)) {
                $this->eliminarDirectorioRecursivo($tempZipFolder);
            }

            return [
                'success' => true,
                'creados' => $creados,
                'actualizados' => $actualizados,
                'errores' => $errores,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            if ($tempZipFolder && file_exists($tempZipFolder)) {
                $this->eliminarDirectorioRecursivo($tempZipFolder);
            }

            return [
                'success' => false,
                'mensaje' => 'Error durante la importación: ' . $e->getMessage(),
                'creados' => 0,
                'actualizados' => 0,
                'errores' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Genera un SKU mnemotécnico automático basado en categoría y correlativo
     */
    protected function generarSkuAutomatico(string $categoriaNombre, ?int $tiendaId): string
    {
        $prefijo = 'FLZ'; // Farmaluz por defecto
        $catLower = Str::lower($categoriaNombre);

        if (Str::contains($catLower, ['fito', 'tintura'])) {
            $prefijo = 'FIT';
        } elseif (Str::contains($catLower, ['magistral', 'formula'])) {
            $prefijo = 'MAG';
        } elseif (Str::contains($catLower, ['dermo', 'piel', 'crema'])) {
            $prefijo = 'DER';
        } elseif (Str::contains($catLower, ['aceite', 'esencial', 'aroma'])) {
            $prefijo = 'ACE';
        } elseif (Str::contains($catLower, ['suplemento', 'capsula', 'vitamina', 'magnesio'])) {
            $prefijo = 'SUP';
        } elseif (Str::contains($catLower, ['jarabe', 'respirat', 'pulmon'])) {
            $prefijo = 'JAR';
        }

        // Buscar el último correlativo numérico con este prefijo
        $ultimoProducto = Producto::where('sku', 'like', "{$prefijo}-%")
            ->orderBy('id', 'desc')
            ->first();

        $numero = 1;
        if ($ultimoProducto && preg_match('/-(\d+)$/', $ultimoProducto->sku, $matches)) {
            $numero = intval($matches[1]) + 1;
        }

        $skuCandidato = sprintf('%s-%04d', $prefijo, $numero);

        // Validar unicidad
        while (Producto::where('sku', $skuCandidato)->exists()) {
            $numero++;
            $skuCandidato = sprintf('%s-%04d', $prefijo, $numero);
        }

        return $skuCandidato;
    }

    /**
     * Procesa la imagen del producto (descarga desde URL o copia desde archivo local/ZIP)
     */
    protected function procesarImagen(string $ref, ?string $zipFolder, string $sku): ?string
    {
        try {
            // 1. Caso URL de Internet
            if (filter_var($ref, FILTER_VALIDATE_URL)) {
                $response = Http::timeout(10)->get($ref);
                if ($response->successful()) {
                    $ext = 'jpg';
                    $contentType = $response->header('Content-Type');
                    if (str_contains($contentType, 'png')) $ext = 'png';
                    elseif (str_contains($contentType, 'webp')) $ext = 'webp';

                    $nombreArchivo = 'productos/' . Str::slug($sku) . '-' . uniqid() . '.' . $ext;
                    Storage::disk('public')->put($nombreArchivo, $response->body());
                    return '/storage/' . $nombreArchivo;
                }
            }

            // 2. Caso Archivo en ZIP cargado
            if ($zipFolder && file_exists($zipFolder)) {
                $rutaLocal = $zipFolder . DIRECTORY_SEPARATOR . $ref;
                // Buscar también de forma insensible a mayúsculas
                if (!file_exists($rutaLocal)) {
                    $archivos = scandir($zipFolder);
                    foreach ($archivos as $archivo) {
                        if (strcasecmp($archivo, $ref) === 0) {
                            $rutaLocal = $zipFolder . DIRECTORY_SEPARATOR . $archivo;
                            break;
                        }
                    }
                }

                if (file_exists($rutaLocal) && is_file($rutaLocal)) {
                    $ext = pathinfo($rutaLocal, PATHINFO_EXTENSION);
                    $nombreArchivo = 'productos/' . Str::slug($sku) . '-' . uniqid() . '.' . $ext;
                    Storage::disk('public')->put($nombreArchivo, file_get_contents($rutaLocal));
                    return '/storage/' . $nombreArchivo;
                }
            }

            // 3. Caso archivo preexistente en public/images/
            $rutaPublica = public_path('images/' . $ref);
            if (file_exists($rutaPublica) && is_file($rutaPublica)) {
                return '/images/' . $ref;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Elimina recursivamente un directorio temporal
     */
    protected function eliminarDirectorioRecursivo($dir): void
    {
        if (!file_exists($dir)) return;
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $target = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($target) ? $this->eliminarDirectorioRecursivo($target) : unlink($target);
        }
        rmdir($dir);
    }
}
