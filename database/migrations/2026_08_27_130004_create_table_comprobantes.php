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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id('id_comprobante');
            $table->unsignedBigInteger('id_pedido')->nullable();
            $table->unsignedBigInteger('id_tipo_comprobante');
            $table->string('numero', 255);
            $table->date('fecha');
            $table->decimal('monto', 12, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos')->onDelete('set null');
            $table->foreign('id_tipo_comprobante')->references('id_tipo_comprobante')->on('tipo_comprobantes')->onDelete('restrict');
        });

        DB::statement("ALTER TABLE comprobantes COMMENT 'El signo (debe/haber) y disparo de comision dependen de tipo_comprobantes.debe / tipo_comprobantes.abreviatura - ver comisiones.id_comprobante'");

        Schema::create('entregas', function (Blueprint $table) {
            $table->id('id_entrega');
            $table->unsignedBigInteger('id_pedido');
            $table->date('fecha');
            $table->string('tipo', 20); // total, parcial, nula
            $table->timestamps();

            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos')->onDelete('cascade');
        });

        Schema::create('comisiones', function (Blueprint $table) {
            $table->id('id_comision');
            $table->unsignedBigInteger('id_comprobante');
            $table->decimal('porcentaje', 5, 2)->default(0.00);
            $table->decimal('monto', 12, 2)->default(0.00);
            $table->date('fecha_calculo');
            $table->timestamps();

            $table->foreign('id_comprobante')->references('id_comprobante')->on('comprobantes')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE comisiones COMMENT 'Formula exacta (fijo por proveedor vs. variable por producto/cliente) pendiente de confirmar con el cliente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisiones');
        Schema::dropIfExists('entregas');
        Schema::dropIfExists('comprobantes');
    }
};
