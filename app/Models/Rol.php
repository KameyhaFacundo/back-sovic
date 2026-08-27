<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'codigo',
    ];

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'rol_permisos', 'id_rol', 'id_permiso');
    }
}
