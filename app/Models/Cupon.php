<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Cupon extends Model
{
    use BelongsToTenant;

    protected $table = 'cupones';

    protected $fillable = [
        'tienda_id',
        'codigo',
        'tipo',
        'valor',
        'monto_minimo',
        'limite_usos',
        'usos_actuales',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'monto_minimo' => 'decimal:2',
        'activo' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function esValido(float $montoSubtotal): bool
    {
        if (!$this->activo) return false;
        if ($this->limite_usos && $this->usos_actuales >= $this->limite_usos) return false;
        
        $ahora = now();
        if ($this->fecha_inicio && $ahora->lt($this->fecha_inicio)) return false;
        if ($this->fecha_fin && $ahora->gt($this->fecha_fin)) return false;
        
        if ($montoSubtotal < $this->monto_minimo) return false;

        return true;
    }

    public function calcularDescuento(float $montoSubtotal): float
    {
        if (!$this->esValido($montoSubtotal)) return 0.00;

        if ($this->tipo === 'porcentaje') {
            return round(($montoSubtotal * ($this->valor / 100)), 2);
        }

        return min($montoSubtotal, (float) $this->valor);
    }
}
