<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToTenant;

class Producto extends Model
{
    use BelongsToTenant;

    protected $table = 'productos';

    protected $fillable = [
        'tienda_id',
        'categoria_id',
        'marca_id',
        'nombre',
        'slug',
        'sku',
        'codigo_barras',
        'descripcion_corta',
        'descripcion_completa',
        'precio_venta',
        'costo_adquisicion',
        'precio_oferta',
        'oferta_inicio',
        'oferta_fin',
        'stock_actual',
        'stock_minimo',
        'peso_kg',
        'tipo',
        'estado',
        'destacado',
        'nuevo',
        'imagen_principal',
        'meta_titulo',
        'meta_descripcion',
    ];

    protected $casts = [
        'precio_venta' => 'decimal:2',
        'costo_adquisicion' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
        'peso_kg' => 'decimal:2',
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
        'destacado' => 'boolean',
        'nuevo' => 'boolean',
        'oferta_inicio' => 'datetime',
        'oferta_fin' => 'datetime',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenProducto::class, 'producto_id')->orderBy('orden');
    }

    public function variantes(): HasMany
    {
        return $this->hasMany(VarianteProducto::class, 'producto_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'producto_id')->latest();
    }

    // Calcula si la oferta está activa actualmente
    public function tieneOfertaActiva(): bool
    {
        if (!$this->precio_oferta || $this->precio_oferta <= 0) {
            return false;
        }

        $ahora = now();

        if ($this->oferta_inicio && $ahora->lt($this->oferta_inicio)) {
            return false;
        }

        if ($this->oferta_fin && $ahora->gt($this->oferta_fin)) {
            return false;
        }

        return true;
    }

    // Retorna el precio vigente real
    public function getPrecioEfectivoAttribute(): float
    {
        return $this->tieneOfertaActiva() ? (float) $this->precio_oferta : (float) $this->precio_venta;
    }
}
