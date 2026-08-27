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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->string('empresa', 255);
            $table->string('domicilio', 255)->nullable();
            $table->string('localidad', 255)->nullable();
            $table->string('provincia', 255)->nullable();
            $table->json('telefonos')->nullable();
            $table->string('codigo_postal', 20)->nullable();
            $table->string('cuit', 13)->nullable();
            $table->decimal('comision_porcentaje', 5, 2)->default(0.00);
            $table->text('descripcion')->nullable();
            $table->json('emails')->nullable();
            $table->string('web', 255)->nullable();
            $table->boolean('pedido_online')->default(false);
            $table->string('logo', 255)->nullable();
            $table->string('carpeta_productos', 255)->nullable();
            $table->string('tipo_formulario', 255)->nullable();
            $table->boolean('habilitado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE proveedores COMMENT 'Las 18 marcas que Sovic representa (Parametros > Representaciones)'");

        Schema::create('rubros', function (Blueprint $table) {
            $table->id('id_rubro');
            $table->unsignedBigInteger('id_proveedor');
            $table->string('descripcion', 255);
            $table->unsignedInteger('orden')->default(0);
            $table->decimal('iva', 5, 2)->default(21.00);
            $table->boolean('incluir_iva')->default(false);
            $table->decimal('impuesto_interno', 5, 2)->default(0.00);
            $table->boolean('visualizar')->default(true);
            $table->timestamps();

            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE rubros COMMENT 'ABM de rubros por marca (Productos > Rubros)'");

        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('id_rubro');
            $table->unsignedBigInteger('id_proveedor')->comment('un producto pertenece a un unico proveedor');
            $table->string('codigo', 255)->nullable();
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->decimal('importe_1', 12, 2)->default(0.00);
            $table->decimal('importe_2', 12, 2)->default(0.00);
            $table->decimal('importe_3', 12, 2)->default(0.00);
            $table->decimal('importe_4', 12, 2)->default(0.00);
            $table->decimal('ancho', 8, 2)->nullable();
            $table->decimal('alto', 8, 2)->nullable();
            $table->decimal('profundidad', 8, 2)->nullable();
            $table->decimal('peso', 8, 3)->nullable();
            $table->string('color', 255)->nullable();
            $table->unsignedInteger('bultos')->default(1);
            $table->boolean('habilitado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_rubro')->references('id_rubro')->on('rubros')->onDelete('cascade');
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE productos COMMENT 'Productos > Listado - importe_1..4 son las 4 columnas de precio de la pantalla real, no una lista dinamica'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
        Schema::dropIfExists('rubros');
        Schema::dropIfExists('proveedores');
    }
};
