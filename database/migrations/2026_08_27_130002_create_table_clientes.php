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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('nombre', 255);
            $table->string('cuit', 13)->nullable()->unique();
            $table->string('domicilio', 255)->nullable();
            $table->string('localidad', 255)->nullable();
            $table->string('provincia', 255)->nullable();
            $table->unsignedBigInteger('id_ruta')->nullable();
            $table->string('codigo_postal', 20)->nullable();
            $table->json('telefonos')->nullable();
            $table->string('contacto', 255)->nullable();
            $table->json('emails')->nullable();
            $table->boolean('habilitado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_ruta')->references('id_ruta')->on('rutas')->onDelete('set null');
        });

        Schema::create('cliente_proveedor_permisos', function (Blueprint $table) {
            $table->id('id_cliente_proveedor_permiso');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_proveedor');
            $table->boolean('habilitado')->default(true);
            $table->timestamps();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('cascade');
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->onDelete('cascade');
            $table->unique(['id_cliente', 'id_proveedor'], 'cliente_proveedor_unique');
        });

        DB::statement("ALTER TABLE cliente_proveedor_permisos COMMENT 'Matriz cliente x proveedor: a que marcas le puede comprar Sovic a cada cliente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_proveedor_permisos');
        Schema::dropIfExists('clientes');
    }
};
