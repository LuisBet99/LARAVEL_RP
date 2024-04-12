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
        Schema::create('tutores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_docente');
            $table->unsignedBigInteger('id_coordinador_institucional')->nullable();
            $table->boolean('mostrar') ;
            $table->foreign('id_docente')->references('id')->on('docentes');
            $table->foreign('id_coordinador_institucional')->references('id')->on('coordinador_institucional');

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
        Schema::dropIfExists('tutores');

    }
};
