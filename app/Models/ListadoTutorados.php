<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListadoTutorados extends Model
{
    use HasFactory;
    protected $table = 'listado_tutorados';
    protected $fillable = ['estatus_tutorado','id_alumno','id_tutor','id_generacion','id_informe_periodo_01','id_informe_periodo_02','id_informe_periodo_03','id_informe_periodo_04','mostrar'];

    public $timestamps = false;
}
