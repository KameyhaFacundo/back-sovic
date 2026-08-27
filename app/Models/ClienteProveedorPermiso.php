<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteProveedorPermiso extends Model
{
    use HasFactory;

    protected $table = 'cliente_proveedor_permisos';

    protected $primaryKey = 'id_cliente_proveedor_permiso';

    protected $fillable = [
        'id_cliente',
        'id_proveedor',
        'habilitado',
    ];

    protected $casts = [
        'habilitado' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente')->withTrashed();
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor')->withTrashed();
    }
}
