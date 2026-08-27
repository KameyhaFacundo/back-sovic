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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id('id_gasto');
            $table->unsignedBigInteger('id_cuenta');
            $table->string('comercio', 255);
            $table->string('iva', 255)->nullable();
            $table->string('cuit', 13)->nullable();
            $table->string('provincia', 255)->nullable();
            $table->date('fecha');
            $table->string('comprobante_tipo', 5)->nullable();
            $table->string('comprobante_punto_venta', 255)->nullable();
            $table->string('comprobante_numero', 255)->nullable();
            $table->unsignedBigInteger('id_forma_pago')->nullable();
            $table->string('numero_pago', 255)->nullable();
            $table->decimal('total', 12, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_cuenta')->references('id_cuenta')->on('cuentas')->onDelete('restrict');
            $table->foreign('id_forma_pago')->references('id_forma_pago')->on('formas_pago')->onDelete('set null');
        });

        DB::statement("ALTER TABLE gastos COMMENT 'Gastos > Alta (cabecera) - comercio es texto libre, no siempre es uno de los 18 proveedores. Ver gasto_items para el detalle de lineas'");

        Schema::create('gasto_items', function (Blueprint $table) {
            $table->id('id_gasto_item');
            $table->unsignedBigInteger('id_gasto');
            $table->string('descripcion', 255);
            $table->decimal('importe', 12, 2)->default(0.00);
            $table->string('codigo', 255)->nullable();
            $table->string('comprobante', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_gasto')->references('id_gasto')->on('gastos')->onDelete('cascade');
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->string('numero_recibo', 255)->nullable();
            $table->string('razon_social', 255);
            $table->string('tipo', 255)->nullable();
            $table->string('cuit', 13)->nullable();
            $table->string('numero_comprobante', 255)->nullable();
            $table->date('fecha');
            $table->unsignedBigInteger('id_forma_pago')->nullable();
            $table->string('numero_pago', 255)->nullable();
            $table->string('corresponde_a', 255)->nullable();
            $table->decimal('importe', 12, 2)->default(0.00);
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->timestamps();

            $table->foreign('id_forma_pago')->references('id_forma_pago')->on('formas_pago')->onDelete('set null');
            $table->foreign('id_usuario')->references('id_usuario')->on('users')->onDelete('set null');
        });

        DB::statement("ALTER TABLE pagos COMMENT 'Gastos > Pagos - registro de pagos/recibos, no es lo mismo que Remesa (descartada en sesion anterior). razon_social es texto libre, no siempre uno de los 18 proveedores. id_forma_pago asumido igual al patron de Gastos, no confirmado literal en esta pantalla'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('gasto_items');
        Schema::dropIfExists('gastos');
    }
};
