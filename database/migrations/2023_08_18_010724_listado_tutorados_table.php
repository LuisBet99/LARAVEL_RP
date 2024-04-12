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
        Schema::create('listado_tutorados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('estatus_tutorado');
            $table->unsignedBigInteger('id_alumno');
            $table->unsignedBigInteger('id_tutor');
            $table->unsignedBigInteger('id_generacion');
            $table->unsignedBigInteger('id_informe_periodo_01');
            $table->unsignedBigInteger('id_informe_periodo_02');
            $table->unsignedBigInteger('id_informe_periodo_03');
            $table->unsignedBigInteger('id_informe_periodo_04');
            $table->boolean('mostrar');

            $table->foreign('id_alumno')->references('id')->on('alumnos');
            $table->foreign('id_tutor')->references('id')->on('tutores');
            $table->foreign('id_generacion')->references('id')->on('generaciones');
            $table->foreign('id_informe_periodo_01')->references('id')->on('informe_periodos');
            $table->foreign('id_informe_periodo_02')->references('id')->on('informe_periodos');
            $table->foreign('id_informe_periodo_03')->references('id')->on('informe_periodos');
            $table->foreign('id_informe_periodo_04')->references('id')->on('informe_periodos');
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
        Schema::dropIfExists('listado_tutorados');

    }
};
