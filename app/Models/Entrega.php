<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_entrega';

    protected $fillable = [
        'id_pedido',
        'fecha',
        'tipo',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}
