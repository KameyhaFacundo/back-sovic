<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioSucursal extends Model
{
    use HasFactory;

    protected $table = 'usuarios_sucursal';

    protected $fillable = [
        'id_usuario',
        'id_sucursal',
    ];

    /**
     * Relación con el usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Relación con la sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }
}
