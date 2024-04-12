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
         Schema::create('tipos_canalizacion_becas', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('nombre');
                $table->string('abreviacion');
                $table->string('clave');

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
            Schema::dropIfExists('tipos_canalizacion_becas');
    }
};
