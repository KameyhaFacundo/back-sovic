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
        Schema::create('comercios', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 255);
            $table->string('nombre_fantasia', 255);
            $table->string('domicilio', 255);
            $table->string('telefono', 255);
            $table->string('persona_responsable', 255);
            $table->unsignedTinyInteger('condicion_afip');
            $table->boolean('habilitado');
            $table->boolean('propio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comercios');
    }
};
