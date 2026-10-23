<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VarianteProducto extends Model
{
    protected $table = 'variantes_producto';

    protected $fillable = [
        'producto_id',
        'nombre',
        'sku_variante',
        'precio_adicional',
        'stock',
        'imagen',
        'activo',
    ];

    protected $casts = [
        'precio_adicional' => 'decimal:2',
        'stock' => 'integer',
        'activo' => 'boolean',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
