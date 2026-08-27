<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'numero_recibo',
        'razon_social',
        'tipo',
        'cuit',
        'numero_comprobante',
        'fecha',
        'id_forma_pago',
        'numero_pago',
        'corresponde_a',
        'importe',
        'id_usuario',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function formaPago()
    {
        return $this->belongsTo(FormaPago::class, 'id_forma_pago');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
