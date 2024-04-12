<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformePeriodos extends Model
{
    use HasFactory;
    protected $table = 'informe_periodos';
    protected $fillable = ['id_fechas_periodos_asignadas','id_primer_informe','id_segundo_informe','id_tercer_informe','id_generacion','mostrar'];

    public $timestamps = false;
}
