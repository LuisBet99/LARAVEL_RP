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
        Schema::create('avisos_principales', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->string('contenido')->nullable();
            $table->string('fecha')->nullable();
            $table->string('imagen_1')->nullable();
            $table->string('imagen_2')->nullable();
            $table->string('imagen_3')->nullable();
            $table->string('url_1')->nullable();
            $table->string('url_2')->nullable();
            $table->string('url_3')->nullable();
            $table->boolean('mostrar')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avisos_principales');
    }
};
