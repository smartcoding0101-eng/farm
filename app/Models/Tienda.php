<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tienda extends Model
{
    protected $table = 'tiendas';

    protected $fillable = [
        'nombre',
        'slug',
        'dominio',
        'logo',
        'telefono',
        'email',
        'moneda',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tienda_user')
            ->withPivot('rol', 'es_predeterminada')
            ->withTimestamps();
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function zonasEnvio(): HasMany
    {
        return $this->hasMany(ZonaEnvio::class);
    }
}
