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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id('id_cuenta');
            $table->string('descripcion', 255);
            $table->boolean('visualizar')->default(true);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE cuentas COMMENT 'Parametros > Cuentas - shape minima inferida del combo CUENTAS visto en el alta de Gastos, sin pantalla real auditada aun'");

        Schema::create('formas_pago', function (Blueprint $table) {
            $table->id('id_forma_pago');
            $table->string('descripcion', 255);
            $table->boolean('visualizar')->default(true);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE formas_pago COMMENT 'Listado de bancos/formas de pago disponibles (Parametros > Formas de Pago) - shape inferida del patron de Comprobantes/Rutas, sin pantalla real auditada aun'");

        Schema::create('rutas', function (Blueprint $table) {
            $table->id('id_ruta');
            $table->string('descripcion', 255);
            $table->boolean('visualizar')->default(true);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE rutas COMMENT 'Zonas de reparto (Parametros > Rutas) - mayoria NOA: Salta, Jujuy, Tucuman'");

        Schema::create('tipo_comprobantes', function (Blueprint $table) {
            $table->id('id_tipo_comprobante');
            $table->string('descripcion', 255);
            $table->string('abreviatura', 10);
            $table->boolean('debe')->default(false);
            $table->boolean('visualizar')->default(true);
            $table->timestamps();

            $table->unique('abreviatura');
        });

        DB::statement("ALTER TABLE tipo_comprobantes COMMENT 'Catalogo de tipos de comprobante (Parametros > Comprobantes) - debe define si el monto suma al debe o al haber del cliente'");

        Schema::create('sucursales', function (Blueprint $table) {
            $table->id('id_sucursal');
            $table->string('descripcion', 255);
            $table->json('telefonos')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE sucursales COMMENT 'Oficinas propias de Sovic, standalone'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales');
        Schema::dropIfExists('tipo_comprobantes');
        Schema::dropIfExists('rutas');
        Schema::dropIfExists('formas_pago');
        Schema::dropIfExists('cuentas');
    }
};
