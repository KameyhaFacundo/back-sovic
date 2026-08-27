<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cuenta';

    protected $fillable = [
        'descripcion',
        'visualizar',
    ];

    protected $casts = [
        'visualizar' => 'boolean',
    ];
}
