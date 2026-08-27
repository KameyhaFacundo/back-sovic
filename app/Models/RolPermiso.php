<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolPermiso extends Model
{
    use HasFactory;
    protected $table = 'rol_permisos';
    protected $primaryKey = 'id_rol_permiso';

    protected $fillable = [
        'id_rol',
        'id_permiso',
    ];

    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'id_permiso', 'id_permiso');
    }
}
