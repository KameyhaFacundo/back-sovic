<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nombre',
        'cuit',
        'domicilio',
        'localidad',
        'provincia',
        'id_ruta',
        'codigo_postal',
        'telefonos',
        'contacto',
        'emails',
        'habilitado',
    ];

    protected $casts = [
        'habilitado' => 'boolean',
        'telefonos' => 'array',
        'emails' => 'array',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_cliente');
    }

    public function proveedoresHabilitados()
    {
        return $this->belongsToMany(Proveedor::class, 'cliente_proveedor_permisos', 'id_cliente', 'id_proveedor')
            ->withPivot('habilitado')
            ->withTimestamps();
    }
}
