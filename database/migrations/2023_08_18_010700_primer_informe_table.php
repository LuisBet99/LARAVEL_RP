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
        Schema::create('primer_informe', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('estatus_atendido')->nullable();
            $table->string('registro_diagnostico')->nullable();
            $table->string('beca')->nullable();
            $table->string('tipo_beca')->nullable();
            $table->string('numero_sesiones')->nullable();
            $table->string('horas_atencion')->nullable();
            $table->string('porcentaje_asistencias')->nullable();
            $table->string('actividad_tutorial')->nullable();
            $table->string('modalidad')->nullable();
            $table->string('situacion_problematica')->nullable();
            $table->string('canalizacion')->nullable(); //si
            $table->string('tipo_canalizacion')->nullable(); //beca
            $table->string('beca_canalizacion')->default('NA')->nullable(); // socienomico, federal.
            $table->string('canalizacion_atendida')->default('NA')->nullable();



            $table->string('observaciones')->nullable();
            $table->boolean('estatus_informe')->nullable()->default(false);
            $table->integer('id_generacion')->nullable();
            $table->integer('id_alumno')->nullable();

            $table->integer('periodo')->nullable();





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
        Schema::dropIfExists('primer_informe');

    }
};
