<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('fechas_periodos_asignadas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_periodo');
            $table->unsignedBigInteger('id_fecha_primera_sesion')->default(1);
            $table->unsignedBigInteger('id_fecha_segunda_sesion')->default(1);
            $table->unsignedBigInteger('id_fecha_tercera_sesion')->default(1);
            $table->unsignedBigInteger('id_generacion')->nullable();

            $table->foreign('id_fecha_primera_sesion')->references('id')->on('fechas_periodos');
            $table->foreign('id_fecha_segunda_sesion')->references('id')->on('fechas_periodos');
            $table->foreign('id_fecha_tercera_sesion')->references('id')->on('fechas_periodos');
            $table->foreign('id_generacion')->references('id')->on('generaciones');

            $table->foreign('id_periodo')->references('id')->on('periodos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('fechas_periodos_asignadas');

    }
};
