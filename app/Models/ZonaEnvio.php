<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class ZonaEnvio extends Model
{
    use BelongsToTenant;

    protected $table = 'zonas_envio';

    protected $fillable = [
        'tienda_id',
        'nombre',
        'departamento',
        'costo_envio',
        'monto_minimo_envio_gratis',
        'tiempo_estimado',
        'activo',
    ];

    protected $casts = [
        'costo_envio' => 'decimal:2',
        'monto_minimo_envio_gratis' => 'decimal:2',
        'activo' => 'boolean',
    ];
}
