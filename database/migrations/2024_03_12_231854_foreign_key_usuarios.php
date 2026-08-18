<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('tipo_usuarios')->references('id')->on('tipo_usuarios')->onDelete('cascade');
        });
        Schema::table('permisos', function (Blueprint $table) {
            $table->foreign('id_tipo_usuario')->references('id')->on('tipo_usuarios')->onDelete('cascade');
        });
        Schema::table('permisos_usuarios', function (Blueprint $table) {
            $table->foreign('id_permiso')->references('id')->on('permisos')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('usuarios_sucursal', function (Blueprint $table) {
            $table->foreign('id_sucursal')->references('id')->on('sucursales_comercio')->onDelete('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
