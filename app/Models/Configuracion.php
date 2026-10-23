<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Configuracion extends Model
{
    use BelongsToTenant;

    protected $table = 'configuraciones';

    protected $fillable = [
        'tienda_id',
        'clave',
        'valor',
        'grupo',
    ];

    public static function get(string $clave, $default = null)
    {
        $config = static::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }

    public static function set(string $clave, $valor, string $grupo = 'general')
    {
        return static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => $valor, 'grupo' => $grupo]
        );
    }
}
