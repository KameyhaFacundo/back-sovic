<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'codigo',
        'grupo'
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'permisos_usuarios', 'id_permiso', 'id_usuario');
    }
}
