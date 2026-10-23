<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CatalogoPdfController extends Controller
{
    /**
     * Descargar o visualizar el Catálogo Virtual en formato PDF.
     */
    public function descargar(Request $request)
    {
        // Obtener categorías con sus productos publicados
        $query = Categoria::with(['productos' => function ($q) {
            $q->where('estado', 'publicado')
              ->with('marca')
              ->orderBy('destacado', 'desc')
              ->orderBy('nombre', 'asc');
        }])->where('activo', true)->orderBy('orden', 'asc');

        if ($request->filled('categoria')) {
            $query->where('slug', $request->input('categoria'));
        }

        $categorias = $query->get()->filter(function ($cat) {
            return $cat->productos->isNotEmpty();
        });

        // Configuración de la tienda
        $tiendaNombre = Configuracion::get('tienda_nombre', 'FARMALUZ - Preparados Magistrales');
        $telefono = Configuracion::get('tienda_telefono', '+591 70012345');
        $email = Configuracion::get('tienda_email', 'contacto@farmaluz.bo');
        $direccion = Configuracion::get('tienda_direccion', 'Av. Busch #1234, Edif. Galeno PB, La Paz - Bolivia');
        $whatsapp = Configuracion::get('tienda_whatsapp', '59170012345');

        // Logo Farmaluz en base64 para renderizado 100% confiable y sin fallos en DomPDF
        $logoPath = public_path('images/farmaluz-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Procesar imágenes de productos a base64 o ruta absoluta
        foreach ($categorias as $cat) {
            foreach ($cat->productos as $producto) {
                $producto->img_src = $this->resolverImagenBase64($producto->imagen_principal);
            }
        }

        $data = [
            'categorias'   => $categorias,
            'tiendaNombre' => $tiendaNombre,
            'telefono'     => $telefono,
            'email'        => $email,
            'direccion'    => $direccion,
            'whatsapp'     => $whatsapp,
            'logoBase64'   => $logoBase64,
            'fechaEmision' => now()->translatedFormat('d \d\e F \d\e Y'),
        ];

        $pdf = Pdf::loadView('pdf.catalogo_virtual', $data);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'dpi'                  => 150,
            'defaultFont'          => 'sans-serif',
        ]);

        $nombreArchivo = 'Catalogo-Virtual-Farmaluz-' . date('Y-m') . '.pdf';

        if ($request->has('ver')) {
            return $pdf->stream($nombreArchivo);
        }

        return $pdf->download($nombreArchivo);
    }

    /**
     * Resuelve la imagen a base64 para garantizar que DomPDF no falle por http/https/chroot
     */
    private function resolverImagenBase64(?string $ruta): ?string
    {
        if (empty($ruta)) {
            return null;
        }

        // Si es una ruta relativa de public o storage
        $cleaned = ltrim($ruta, '/');
        $localPath = public_path($cleaned);

        if (file_exists($localPath)) {
            $mime = mime_content_type($localPath) ?: 'image/jpeg';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($localPath));
        }

        // Intentar en public_path('images/...')
        $filename = basename($ruta);
        $imagePath = public_path('images/' . $filename);
        if (file_exists($imagePath)) {
            $mime = mime_content_type($imagePath) ?: 'image/jpeg';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($imagePath));
        }

        return null;
    }
}
