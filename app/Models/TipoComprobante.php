<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_comprobante';

    protected $fillable = [
        'descripcion',
        'abreviatura',
        'debe',
        'visualizar',
    ];

    protected $casts = [
        'debe' => 'boolean',
        'visualizar' => 'boolean',
    ];

    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class, 'id_tipo_comprobante');
    }
}
