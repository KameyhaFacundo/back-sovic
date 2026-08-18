<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroCajaExchange extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'registro_caja_exchange';

    protected $fillable = [
        'id_usuario',
        'es_tesoreria',
        'apertura_caja_at',
        'cierre_caja_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function movimientosOrigen()
    {
        return $this->hasMany(MovimientoCajaExchange::class, 'id_registro_caja_origen');
    }

    public function movimientosDestino()
    {
        return $this->hasMany(MovimientoCajaExchange::class, 'id_registro_caja_destino');
    }

    public function balances()
    {
        return $this->hasMany(BalanceCajaExchange::class, 'id_registro_caja');
    }

        /**
     * Obtiene el registro de caja que es tesorería (único).
     *
     * @return RegistroCajaExchange|null
     */
    public static function obtenerTesoreria()
    {
        return self::where('es_tesoreria', 'T')->orderBy('id', 'desc')->first();
    }


}
