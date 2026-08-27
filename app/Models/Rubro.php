<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_rubro';

    protected $fillable = [
        'id_proveedor',
        'descripcion',
        'orden',
        'iva',
        'incluir_iva',
        'impuesto_interno',
        'visualizar',
    ];

    protected $casts = [
        'incluir_iva' => 'boolean',
        'visualizar' => 'boolean',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor')->withTrashed();
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_rubro');
    }
}
