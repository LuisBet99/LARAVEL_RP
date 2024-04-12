<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisosPrincipales extends Model
{
    use HasFactory;
    // $table->string('titulo');
    // $table->string('contenido');
    // $table->string('fecha');
    // $table->string('imagen_1');
    // $table->string('imagen_2');
    // $table->string('imagen_3');
    // $table->string('url_1');
    // $table->string('url_2');
    // $table->string('url_3');
    protected $table = 'avisos_principales';
    protected $fillable = ['titulo','contenido','fecha','imagen_1','imagen_2','imagen_3','url_1','url_2','url_3','mostrar'];

    public $timestamps = false;
}
