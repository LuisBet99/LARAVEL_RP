<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumnos extends Model
{
    use HasFactory;
    protected $table = 'alumnos';
    protected $fillable = ['nombre','apellido_paterno','apellido_materno','numero_checador','estatus_actual','semestre','especialidad','id_carrera','mostrar','id_usuario','id_generacion','grupo'];

    public $timestamps = false;
}
