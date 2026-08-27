<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Oficinas propias de Sovic (tabla `sucursales`) — standalone, sin FK a proveedores.
 * No confundir con el modelo `Sucursal` (tabla `sucursales_comercio`, del esqueleto de auth/Comercio).
 */
class Oficina extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sucursales';

    protected $primaryKey = 'id_sucursal';

    protected $fillable = [
        'descripcion',
        'telefonos',
    ];

    protected $casts = [
        'telefonos' => 'array',
    ];
}
