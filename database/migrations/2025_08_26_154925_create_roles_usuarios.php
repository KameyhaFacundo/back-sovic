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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique()->index();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
        });

        Schema::create('rol_permisos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol')->index();
            $table->unsignedBigInteger('id_permiso')->index();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rol')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_rol');
        });

        Schema::dropIfExists('rol_permisos');
        Schema::dropIfExists('roles');
    }
};
