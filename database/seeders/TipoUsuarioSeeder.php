<?php

namespace Database\Seeders;

use App\Models\TipoUsuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoUsuario::create([
            'codigo' => 'USR',
            'detalle' => 'Usuario del sistema',
        ]);

        TipoUsuario::create([
            'codigo' => 'CLI',
            'detalle' => 'Usuario cliente',
        ]);
    }
}
