<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, Auditable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, AuditingAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identificador',
        'nombre_completo',
        'email',
        'email_verified_at',
        'password',
        'tipo_usuarios',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuarios');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function permisosUsuarios()
    {
        return $this->hasMany(PermisoUsuario::class, 'id_usuario')->with('permiso');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permisos_usuarios', 'id_usuario', 'id_permiso');
    }

    public function chequearPermisos($permisos)
    {
        $permisosRol = $this->id_rol ? $this->rol->permisos()->pluck('codigo')->toArray() : [];

        $permisosUsuarioRela = $this->permisosUsuarios()->with('permiso')->get()->pluck('permiso.codigo')->toArray();

        $codigos = array_unique(array_merge($permisosRol, $permisosUsuarioRela));

        // Verifica que todos los permisos requeridos estén en los códigos del usuario
        return array_diff($permisos, $codigos);
    }

    public function sucursales()
    {
        return $this->hasManyThrough(Sucursal::class, UsuarioSucursal::class, 'id_usuario', 'id', 'id', 'id_sucursal')->with('comercio');
    }

}
