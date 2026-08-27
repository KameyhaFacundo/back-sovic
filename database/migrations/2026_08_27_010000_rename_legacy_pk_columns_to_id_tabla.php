<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Give rol_permisos its own primary key + timestamps.
        Schema::table('rol_permisos', function (Blueprint $table) {
            $table->id()->first();
            $table->timestamps();
        });

        // 2. Drop every FK that points at a column we're about to rename.
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tipo_usuarios']);
        });
        Schema::table('permisos_usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_permiso']);
            $table->dropForeign(['id_usuario']);
        });
        Schema::table('sucursales_comercio', function (Blueprint $table) {
            $table->dropForeign(['id_comercio']);
        });
        Schema::table('usuarios_sucursal', function (Blueprint $table) {
            $table->dropForeign(['id_sucursal']);
            $table->dropForeign(['id_usuario']);
        });

        // 3. Rename the PK (and the one misnamed FK column) in place.
        DB::statement('ALTER TABLE `users` CHANGE `id` `id_usuario` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `users` CHANGE `tipo_usuarios` `id_tipo_usuario` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `comercios` CHANGE `id` `id_comercio` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `tipo_usuarios` CHANGE `id` `id_tipo_usuario` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `permisos` CHANGE `id` `id_permiso` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `permisos_usuarios` CHANGE `id` `id_permiso_usuario` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `roles` CHANGE `id` `id_rol` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `sucursales_comercio` CHANGE `id` `id_sucursal` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `usuarios_sucursal` CHANGE `id` `id_usuario_sucursal` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `rol_permisos` CHANGE `id` `id_rol_permiso` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

        // 4. Recreate the FKs against the renamed columns (rol_permisos gets its FKs fresh, no add/drop churn).
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_tipo_usuario')->references('id_tipo_usuario')->on('tipo_usuarios')->onDelete('cascade');
        });
        Schema::table('permisos_usuarios', function (Blueprint $table) {
            $table->foreign('id_permiso')->references('id_permiso')->on('permisos')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id_usuario')->on('users')->onDelete('cascade');
        });
        Schema::table('sucursales_comercio', function (Blueprint $table) {
            $table->foreign('id_comercio')->references('id_comercio')->on('comercios')->onDelete('cascade');
        });
        Schema::table('usuarios_sucursal', function (Blueprint $table) {
            $table->foreign('id_sucursal')->references('id_sucursal')->on('sucursales_comercio')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id_usuario')->on('users')->onDelete('cascade');
        });
        Schema::table('rol_permisos', function (Blueprint $table) {
            $table->foreign('id_rol')->references('id_rol')->on('roles')->onDelete('cascade');
            $table->foreign('id_permiso')->references('id_permiso')->on('permisos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_usuario']);
        });
        Schema::table('permisos_usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_permiso']);
            $table->dropForeign(['id_usuario']);
        });
        Schema::table('sucursales_comercio', function (Blueprint $table) {
            $table->dropForeign(['id_comercio']);
        });
        Schema::table('usuarios_sucursal', function (Blueprint $table) {
            $table->dropForeign(['id_sucursal']);
            $table->dropForeign(['id_usuario']);
        });
        Schema::table('rol_permisos', function (Blueprint $table) {
            $table->dropForeign(['id_rol']);
            $table->dropForeign(['id_permiso']);
        });

        DB::statement('ALTER TABLE `users` CHANGE `id_usuario` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `users` CHANGE `id_tipo_usuario` `tipo_usuarios` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `comercios` CHANGE `id_comercio` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `tipo_usuarios` CHANGE `id_tipo_usuario` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `permisos` CHANGE `id_permiso` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `permisos_usuarios` CHANGE `id_permiso_usuario` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `roles` CHANGE `id_rol` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `sucursales_comercio` CHANGE `id_sucursal` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `usuarios_sucursal` CHANGE `id_usuario_sucursal` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `rol_permisos` CHANGE `id_rol_permiso` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('tipo_usuarios')->references('id')->on('tipo_usuarios')->onDelete('cascade');
        });
        Schema::table('permisos_usuarios', function (Blueprint $table) {
            $table->foreign('id_permiso')->references('id')->on('permisos')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('sucursales_comercio', function (Blueprint $table) {
            $table->foreign('id_comercio')->references('id')->on('comercios')->onDelete('cascade');
        });
        Schema::table('usuarios_sucursal', function (Blueprint $table) {
            $table->foreign('id_sucursal')->references('id')->on('sucursales_comercio')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('rol_permisos', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->dropColumn('id');
        });
    }
};
