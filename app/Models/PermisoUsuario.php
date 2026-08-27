<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    use HasFactory;
    protected $table = 'permisos_usuarios';
    protected $primaryKey = 'id_permiso_usuario';
    protected $fillable = [
        'id_permiso',
        'id_usuario',
    ];

    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'id_permiso', 'id_permiso');
    }
}
