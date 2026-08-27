<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_gasto';

    protected $fillable = [
        'id_cuenta',
        'comercio',
        'iva',
        'cuit',
        'provincia',
        'fecha',
        'comprobante_tipo',
        'comprobante_punto_venta',
        'comprobante_numero',
        'id_forma_pago',
        'numero_pago',
        'total',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'id_cuenta');
    }

    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'id_forma_pago');
    }

    public function items()
    {
        return $this->hasMany(GastoItem::class, 'id_gasto');
    }
}
