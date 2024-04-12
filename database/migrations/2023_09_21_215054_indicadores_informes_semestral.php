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
        Schema::create('nombre_de_la_tabla', function (Blueprint $table) {
            $table->id();
            $table->string('Diagnostico');
            $table->string('Ficha_de_registro');
            $table->string('Concatenar_Canalizacion_Realizada');
            $table->string('Concatenar_Canalizacion_Atencion');
            $table->string('Canalizacion_en_Sesion_1');
            $table->string('Canalizacion_en_Sesion_2');
            $table->string('Canalizacion_en_Sesion_3');
            $table->string('Area_de_Canalizacion_Sesion_1');
            $table->string('Area_de_Canalizacion_Sesion_2');
            $table->string('Area_de_Canalizacion_Sesion_3');
            $table->string('Area_de_Canalizacion_B');
            $table->string('Area_de_Canalizacion_S');
            $table->string('Area_de_Canalizacion_P');
            $table->string('Area_de_Canalizacion_AS');
            $table->string('Area_de_Canalizacion_O');
            $table->string('Area_de_Canalizacion_NA');
            $table->string('Canalizados_a_mas_de_un_area');
            $table->string('Atendidos');
            $table->string('Atendidos_Academico');
            $table->string('Atendidos_Psicologia');
            $table->string('Asistencia_de_3_o_mas');
            $table->string('Asistencia_de_2');
            $table->string('Asistencia_de_1');
            $table->string('Asistencia_de_0');
            $table->string('Concatenacion_modalidad');
            $table->string('Modalidad_Atencion_GRUPAL');
            $table->string('Modalidad_Atencion_INDIVIDUAL');
            $table->string('Modalidad_Atencion_AMBAS');
            $table->string('Modalidad_Atencion_NA');
            $table->string('ACTIVIDAD_TUTORIAL_Clave_Preponderante_1');
            $table->string('ACTIVIDAD_TUTORIAL_Clave_Preponderante_2');
            $table->string('ACTIVIDAD_TUTORIAL_Clave_Preponderante_3');
            $table->string('ACTIVIDAD_TUTORIAL_Clave_Preponderante_4');
            $table->string('ACTIVIDAD_TUTORIAL_Clave_Preponderante_5');
            $table->string('ACTIVIDAD_TUTORIAL_Clave_Preponderante_6');
            $table->string('ACTIVIDAD_TUTORIAL_Clave_Preponderante_7');
            $table->string('ACTIVIDAD_TUTORIAL_Clave_Preponderante_0');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_N');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_I');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_S');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_F');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_E');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_O');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_A');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_R');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_P');
            $table->string('DPACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_DP');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_B');
            $table->string('ACTIVIDAD_PROBLEMÁTICA_Clave_Preponderante_NA');
            $table->string('LOGRO_ALCANZADO_SA');
            $table->string('LOGRO_ALCANZADO_SP');
            $table->string('LOGRO_ALCANZADO_SF');
            $table->string('LOGRO_ALCANZADO_SE');
            $table->string('LOGRO_ALCANZADO_NA');

            $table->unsignedBigInteger('id_generacion');
            $table->unsignedBigInteger('id_periodo');
            $table->boolean('mostrar');

            $table->foreign('id_periodo')->references('id')->on('periodos');
            $table->foreign('id_generacion')->references('id')->on('generaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nombre_de_la_tabla');
    }
};
