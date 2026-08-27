<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pedido_item';

    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad_pedida',
        'cantidad_entregada',
        'precio_unitario',
        'descuento_comercial',
        'descuento_volumen',
        'descuento_publicidad',
        'descuento_contado',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto')->withTrashed();
    }
}
