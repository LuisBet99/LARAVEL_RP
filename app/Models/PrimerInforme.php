<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimerInforme extends Model
{
    use HasFactory;
    protected $table = 'primer_informe';
    protected $fillable = ['estatus_atendido','registro_diagnostico','beca','tipo_beca','numero_sesiones','horas_atencion','porcentaje_asistencias','actividad_tutorial','modalidad'
    ,'situacion_problematica','canalizacion','tipo_canalizacion','beca_canalizacion','canalizacion_atendida','observaciones','estatus_informe','id_generacion','periodo','id_alumno'];

    public $timestamps = false;
}
