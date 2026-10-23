<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToTenant;

class Categoria extends Model
{
    use BelongsToTenant;

    protected $table = 'categorias';

    protected $fillable = [
        'tienda_id',
        'categoria_padre_id',
        'nombre',
        'slug',
        'descripcion',
        'imagen',
        'icono',
        'orden',
        'activo',
        'destacada',
        'meta_titulo',
        'meta_descripcion',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'destacada' => 'boolean',
        'orden' => 'integer',
    ];

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_padre_id');
    }

    public function subcategorias(): HasMany
    {
        return $this->hasMany(Categoria::class, 'categoria_padre_id')->where('activo', true)->orderBy('orden');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}
