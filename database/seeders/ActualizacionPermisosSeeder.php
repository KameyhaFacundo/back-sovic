<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActualizacionPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PermisosSeeder::PERMISOS_LISTA as $permiso) {
            Permiso::updateOrCreate(
                ['codigo' => $permiso['codigo']],
                [
                    'nombre' => $permiso['nombre'],
                    'grupo' => $permiso['grupo']
                ]
            );
        }
    }
}