<?php

namespace Database\Seeders;

use App\Models\Comercio;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SucursalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $comercio = Comercio::create([
            'razon_social' => 'Comercio Principal',
            'nombre_fantasia' => 'Comercio base',
            'domicilio' => 'Calle ejemplo 123',
            'telefono' => '123456789',
            'persona_responsable' => 'Responsable',
            'condicion_afip' => 1,
            'cuit' => '20-12345678-9',
            'habilitado' => true,
            'propio' => true,
            'email' => 'comercio@ejemplo.com'
        ]);

        $sucursal  = $comercio->sucursales()->create([
            'descripcion' => 'Sucursal Principal',
            'telefono' => '987654321',
        ]);
    }
}
