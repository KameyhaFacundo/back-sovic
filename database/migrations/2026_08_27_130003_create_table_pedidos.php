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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_proveedor');
            $table->date('fecha_pedido');
            $table->string('condicion_venta')->nullable();
            $table->string('cuenta_bancaria')->nullable();
            $table->enum('estado', ['nuevo', 'procesado', 'liquidado'])->default('nuevo');
            $table->timestamps();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('cascade');
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->onDelete('cascade');
        });

        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id('id_pedido_item');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedInteger('cantidad_pedida')->default(0);
            $table->unsignedInteger('cantidad_entregada')->default(0);
            $table->decimal('precio_unitario', 12, 2)->default(0.00);
            $table->decimal('descuento_comercial', 5, 2)->default(0.00);
            $table->decimal('descuento_volumen', 5, 2)->default(0.00);
            $table->decimal('descuento_publicidad', 5, 2)->default(0.00);
            $table->decimal('descuento_contado', 5, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE pedido_items COMMENT '4 de los 8 descuentos de la cascada como ejemplo - completar Juego/Ofertas/Extra x2 cuando se confirme la formula con el cliente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
        Schema::dropIfExists('pedidos');
    }
};
