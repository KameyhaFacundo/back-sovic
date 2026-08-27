<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_proveedor';

    protected $fillable = [
        'empresa',
        'domicilio',
        'localidad',
        'provincia',
        'telefonos',
        'codigo_postal',
        'cuit',
        'comision_porcentaje',
        'descripcion',
        'emails',
        'web',
        'pedido_online',
        'logo',
        'carpeta_productos',
        'tipo_formulario',
        'habilitado',
    ];

    protected $casts = [
        'habilitado' => 'boolean',
        'pedido_online' => 'boolean',
        'comision_porcentaje' => 'decimal:2',
        'telefonos' => 'array',
        'emails' => 'array',
    ];

    public function rubros()
    {
        return $this->hasMany(Rubro::class, 'id_proveedor');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_proveedor');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_proveedor');
    }

    public function clientesHabilitados()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_proveedor_permisos', 'id_proveedor', 'id_cliente')
            ->withPivot('habilitado')
            ->withTimestamps();
    }
}
