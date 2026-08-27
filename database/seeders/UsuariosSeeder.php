<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UsuarioSucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'identificador' => "admin",
            'nombre_completo' => "Administrador",
            'email' => "admin@example.com",
            'password' => "admin123",
            'id_tipo_usuario' => "1",
            'is_admin' => true,
        ]);

        UsuarioSucursal::create([
            'id_usuario' => 1,
            'id_sucursal' => 1,
        ]);
    }
}