<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_ruta';

    protected $fillable = [
        'descripcion',
        'visualizar',
    ];

    protected $casts = [
        'visualizar' => 'boolean',
    ];
}
