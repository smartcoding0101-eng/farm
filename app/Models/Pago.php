<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'pedido_id',
        'referencia_pago',
        'metodo',
        'monto',
        'comprobante_archivo',
        'datos_transaccion',
        'estado',
        'verificado_por',
        'fecha_verificacion',
        'motivo_rechazo',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_verificacion' => 'datetime',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }
}
