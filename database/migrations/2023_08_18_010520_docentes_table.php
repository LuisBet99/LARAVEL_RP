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
        Schema::create('docentes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('numero_checador');
            $table->string('correo_institucional')->nullable();
            $table->boolean('mostrar') ;
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_carrera');

            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_carrera')->references('id')->on('carreras');


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
        Schema::dropIfExists('docentes');

    }
};
