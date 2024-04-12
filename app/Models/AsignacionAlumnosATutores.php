<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionAlumnosATutores extends Model
{
    use HasFactory;
    protected $table = 'asignacion_alumnos_a_tutores';
    protected $fillable = [
        'id_generacion',
        'id_coordinador_tutorias',
        'id_carrera',
        'data',
        'mostrar',
    ];
    public $timestamps = false;
}
