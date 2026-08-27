<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'id_rubro',
        'id_proveedor',
        'codigo',
        'nombre',
        'descripcion',
        'importe_1',
        'importe_2',
        'importe_3',
        'importe_4',
        'ancho',
        'alto',
        'profundidad',
        'peso',
        'color',
        'bultos',
        'habilitado',
    ];

    protected $casts = [
        'habilitado' => 'boolean',
    ];

    public function rubro()
    {
        return $this->belongsTo(Rubro::class, 'id_rubro');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor')->withTrashed();
    }

    public function pedidoItems()
    {
        return $this->hasMany(PedidoItem::class, 'id_producto');
    }
}
