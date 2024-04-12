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
        Schema::create('asignacion_alumnos_a_tutores', function (Blueprint $table) {
            $table->id();
            $table->string('id_generacion');
            $table->string('id_coordinador_tutorias');
            $table->string('id_carrera');
            $table->string('data');
            $table->string('mostrar');




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignacion_alumnos_a_tutores');
    }
};
