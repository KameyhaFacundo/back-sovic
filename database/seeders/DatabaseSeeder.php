<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TipoUsuarioSeeder::class);
        $this->call(PermisosSeeder::class);
        $this->call(SucursalesSeeder::class);
        $this->call(UsuariosSeeder::class);
    }
}