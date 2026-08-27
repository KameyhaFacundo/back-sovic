<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_cliente',
        'id_proveedor',
        'fecha_pedido',
        'condicion_venta',
        'cuenta_bancaria',
        'estado',
    ];

    protected $casts = [
        'fecha_pedido' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente')->withTrashed();
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class, 'id_pedido');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'id_pedido');
    }

    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class, 'id_pedido');
    }
}
