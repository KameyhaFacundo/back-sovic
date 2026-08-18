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
        ['nombre' => 'Impersonar usuarios', 'codigo' => 'impersonate-usuarios', 'grupo' => 'usuarios'],

        // ROLES (PERMISOS DE ADMINISTRACION DE ROLES)
        ['nombre' => 'Listar roles', 'codigo' => 'list-roles', 'grupo' => 'roles'],
        ['nombre' => 'Ver roles', 'codigo' => 'view-roles', 'grupo' => 'roles'],
        ['nombre' => 'Crear roles', 'codigo' => 'create-roles', 'grupo' => 'roles'],
        ['nombre' => 'Actualizar roles', 'codigo' => 'update-roles', 'grupo' => 'roles'],
        ['nombre' => 'Eliminar roles', 'codigo' => 'delete-roles', 'grupo' => 'roles'],
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
            $permisosArray[] = ['id_rol' => $rol->id, 'id_permiso' => $p->id];
        }
        DB::table('rol_permisos')->insert($permisosArray);
    }
}