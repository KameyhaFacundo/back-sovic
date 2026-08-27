<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_comprobante';

    protected $fillable = [
        'id_pedido',
        'id_tipo_comprobante',
        'numero',
        'fecha',
        'monto',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function tipoComprobante()
    {
        return $this->belongsTo(TipoComprobante::class, 'id_tipo_comprobante');
    }

    public function comision()
    {
        return $this->hasOne(Comision::class, 'id_comprobante');
    }
}
