<?php

namespace App\Models;

use App\Interfaces\Filtros\FiltroTipoUsuarioEntidadesInterface;
use App\Traits\FiltroTipoUsuarioEntidadesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comercio extends Model implements FiltroTipoUsuarioEntidadesInterface
{
    use HasFactory, SoftDeletes, FiltroTipoUsuarioEntidadesTrait;

    protected $fillable = [
        'razon_social',
        'nombre_fantasia',
        'domicilio',
        'email',
        'telefono',
        'persona_responsable',
        'condicion_afip',
        'cuit',
        'habilitado',
        'propio',
    ];

    protected $casts = [
        'propio' => 'boolean',
        'habilitado' => 'boolean',
    ];

    public function planesComercio()
    {
        return $this->hasMany(PlanComercio::class, 'id_comercio');
    }

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class, 'id_comercio');
    }
    public function autorizaciones()
    {
        return $this->hasMany(Autorizacion::class, 'id_comercio');
    }
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'id_comercio');
    }

    public function scopeFiltroTipoUsuario($query, User $user, $request = null)
    {
        $tipoUsuarioCodigo = $user->tipoUsuario->codigo;
        $sucursalUsuario = $user->sucursales->first();

        if ($tipoUsuarioCodigo === 'CLI' && !$sucursalUsuario->comercio->propio) {
            $query->where('id', $sucursalUsuario->comercio->id);
        }

        return $query;
    }
}
