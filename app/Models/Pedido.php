<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToTenant;

class Pedido extends Model
{
    use BelongsToTenant;

    protected $table = 'pedidos';

    protected $fillable = [
        'tienda_id',
        'numero_pedido',
        'user_id',
        'cliente_nombre',
        'cliente_email',
        'cliente_telefono',
        'modalidad_entrega',
        'zona_envio_id',
        'departamento',
        'ciudad',
        'direccion_envio',
        'referencia_envio',
        'subtotal',
        'descuento',
        'costo_envio',
        'total',
        'cupon_aplicado',
        'estado_pedido',
        'metodo_pago',
        'estado_pago',
        'notas_cliente',
        'notas_administrador',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'costo_envio' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function zonaEnvio(): BelongsTo
    {
        return $this->belongsTo(ZonaEnvio::class, 'zona_envio_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'pedido_id');
    }
}
