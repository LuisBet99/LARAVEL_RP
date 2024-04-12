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
        Schema::create('tutor_asignado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('mostrar');
            $table->unsignedBigInteger('id_tutor');
            $table->unsignedBigInteger('id_carrera');

            $table->foreign('id_tutor')->references('id')->on('tutores');
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
        Schema::dropIfExists('tutor_asignado');

    }
};
