<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_gasto_item';

    protected $fillable = [
        'id_gasto',
        'descripcion',
        'importe',
        'codigo',
        'comprobante',
    ];

    public function gasto()
    {
        return $this->belongsTo(Gasto::class, 'id_gasto');
    }
}
