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
        Schema::create('informe_periodos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_fechas_periodos_asignadas');
            $table->unsignedBigInteger('id_primer_informe');
            $table->unsignedBigInteger('id_segundo_informe');
            $table->unsignedBigInteger('id_tercer_informe');
            $table->bigInteger('id_generacion')->nullable();
            $table->boolean('mostrar');

            $table->foreign('id_fechas_periodos_asignadas')->references('id')->on('fechas_periodos_asignadas');
            $table->foreign('id_primer_informe')->references('id')->on('primer_informe');
            $table->foreign('id_segundo_informe')->references('id')->on('segundo_informe');
            $table->foreign('id_tercer_informe')->references('id')->on('tercer_informe');

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
        Schema::dropIfExists('informe_periodos');

    }
};
