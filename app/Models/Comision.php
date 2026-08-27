<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;

    protected $table = 'comisiones';

    protected $primaryKey = 'id_comision';

    protected $fillable = [
        'id_comprobante',
        'porcentaje',
        'monto',
        'fecha_calculo',
    ];

    protected $casts = [
        'fecha_calculo' => 'date',
    ];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'id_comprobante');
    }
}
