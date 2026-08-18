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
        Schema::create('sucursales_comercio', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 255);
            $table->unsignedBigInteger('id_comercio');
            $table->string('telefono');
            $table->timestamps();

            $table->foreign('id_comercio')->references('id')->on('comercios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales_comercio');
    }
};
