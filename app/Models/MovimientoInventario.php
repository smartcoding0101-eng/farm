<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToTenant;

class MovimientoInventario extends Model
{
    use BelongsToTenant;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'tienda_id',
        'producto_id',
        'variante_id',
        'user_id',
        'pedido_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_posterior',
        'motivo',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(VarianteProducto::class, 'variante_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}
