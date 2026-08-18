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
        'tipo_usuario_exchange'
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

    public const TIPO_USUARIO_EXCHANGE = [
        'T',
        'C'
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

    public function registroCajaExchange()
    {
        return $this->hasMany(RegistroCajaExchange::class, 'id_usuario');
    }

    public function getRegistroCajaActualExchangeAttribute()
    {
        if ($this->tipo_usuario_exchange == 'T') {
            return RegistroCajaExchange::where('es_tesoreria', 'T')
                ->whereNull('cierre_caja_at')
                ->latest('apertura_caja_at')
                ->first();
        }

        return $this->registroCajaExchange()
            ->whereNull('cierre_caja_at')
            ->latest('apertura_caja_at')
            ->first();
    }
    // public function registroCajaActualExchange()
    // {
    //     if ($this->tipo_usuario_exchange === 'T') {
    //         return RegistroCajaExchange::where('es_tesoreria', 'T')
    //             ->whereNull('cierre_caja_at')
    //             ->latest('apertura_caja_at');
    //     }
    //     return $this->hasOne(RegistroCajaExchange::class, 'id_usuario')
    //         ->whereNull('cierre_caja_at')
    //         ->latest('apertura_caja_at');
    // }
}
