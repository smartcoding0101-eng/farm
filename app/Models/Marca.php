<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToTenant;

class Marca extends Model
{
    use BelongsToTenant;

    protected $table = 'marcas';

    protected $fillable = [
        'tienda_id',
        'nombre',
        'slug',
        'logo',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'marca_id');
    }
}
