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
        Schema::create('reporte_semestral_individual', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('numero_sesiones_totales');
            $table->integer('numero_horas_totales');
            $table->integer('numero_total_asistencias');
            $table->string('clave_prep_actividad');
            $table->string('clave_prep_situacion'); //
            $table->string('clave_prep_logros');
            $table->string('observaciones');

            $table->string('totalNumeroSesiones');
            $table->string('porcentajeTotalNumeroAsistencias');
            $table->string('totalNumeroHorasAtencion');
            $table->string('totalNumeroCanalizaciones');
            $table->string('actividadTutorialPrep');
            $table->string('modalidadPrep');
            $table->string('situacionProblematicaPrep');
            $table->string('tipoCanalizacionPrep');
            $table->string('becaCanalizacionPrep');
            $table->string('canalizacionAtendidaPrep');
            $table->string('fecha')->nullable();
            $table->string('fecha_ultima_modificacion')->nullable();
            $table->integer('id_alumno');
            $table->integer('id_tutor');
            $table->integer('periodo')->nullable();
            $table->integer('id_generacion');

            $table->boolean('mostrar')->default(true);


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
        Schema::dropIfExists('reporte_semestral_individual');

    }
};
