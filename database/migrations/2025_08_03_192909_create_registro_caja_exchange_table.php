<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroCajaExchangeTable extends Migration
{
    public function up(): void
    {
        Schema::create('registro_caja_exchange', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->char('es_tesoreria', 1);
            $table->dateTime('apertura_caja_at');
            $table->dateTime('cierre_caja_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_caja_exchange');
    }
}
