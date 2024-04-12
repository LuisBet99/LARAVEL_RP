<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinadorInstitucional extends Model
{
    use HasFactory;
    protected $table = 'coordinador_institucional';
    protected $fillable = ['nombre','apellido_paterno','apellido_materno','numero_checador','id_carrera','mostrar','id_usuario',];

    public $timestamps = false;
}
