<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    final public const PERMISOS_LISTA = [
        // USUARIOS
        ['nombre' => 'Listar usuarios', 'codigo' => 'list-usuarios', 'grupo' => 'usuarios'],
        ['nombre' => 'Ver usuarios', 'codigo' => 'view-usuarios', 'grupo' => 'usuarios'],
        ['nombre' => 'Crear usuarios', 'codigo' => 'create-usuarios', 'grupo' => 'usuarios'],
        ['nombre' => 'Actualizar usuarios', 'codigo' => 'update-usuarios', 'grupo' => 'usuarios'],
        ['nombre' => 'Borrar usuarios', 'codigo' => 'delete-usuarios', 'grupo' => 'usuarios'],

        // RUTAS
        ['nombre' => 'Listar rutas', 'codigo' => 'list-rutas', 'grupo' => 'rutas'],
        ['nombre' => 'Ver rutas', 'codigo' => 'view-rutas', 'grupo' => 'rutas'],
        ['nombre' => 'Crear rutas', 'codigo' => 'create-rutas', 'grupo' => 'rutas'],
        ['nombre' => 'Actualizar rutas', 'codigo' => 'update-rutas', 'grupo' => 'rutas'],
        ['nombre' => 'Borrar rutas', 'codigo' => 'delete-rutas', 'grupo' => 'rutas'],
    ];

    public function run()
    {
        DB::table('permisos')->insert(self::PERMISOS_LISTA);

        //ROLES
        $roles = [
            ['nombre' => 'Administrador', 'codigo' => 'admin', 'descripcion' => 'tiene todos los permisos'],
        ];

        $permisos = DB::table('permisos')->get();

        foreach ($roles as $rol) {
            DB::table('roles')->insert($rol);
        }

        $rol = DB::table('roles')->where('codigo', 'admin')->first();

        $permisosArray = [];
        foreach ($permisos as $p) {
            $permisosArray[] = ['id_rol' => $rol->id_rol, 'id_permiso' => $p->id_permiso];
        }
        DB::table('rol_permisos')->insert($permisosArray);
    }
}