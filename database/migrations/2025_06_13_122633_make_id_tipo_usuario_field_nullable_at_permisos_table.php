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
        Schema::table('permisos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tipo_usuario')->nullable()->change();
            $table->string('codigo')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permisos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tipo_usuario')->nullable(false)->change();
            $table->string('codigo', 5)->change();
        });
    }
};
