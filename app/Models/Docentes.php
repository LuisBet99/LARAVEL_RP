<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docentes extends Model
{
    use HasFactory;
    protected $table = 'docentes';
    protected $fillable = ['nombre','apellido_paterno','apellido_materno','numero_checador','mostrar','id_usuario','correo_institucional','id_carrera'];

    public $timestamps = false;
}
