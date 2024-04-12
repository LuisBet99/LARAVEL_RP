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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('numero_checador');
            $table->string('estatus_actual');
            $table->string('semestre');
            $table->string('especialidad');
            $table->string('id_carrera');
            $table->string('sexo')->nullable()->default('N/A');
            $table->boolean('mostrar');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_generacion');
            $table->string('grupo')->nullable()->default('N/A');

            $table->foreign('id_usuario')->references('id')->on('users');
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
        //
        Schema::dropIfExists('alumnos');

    }
};
