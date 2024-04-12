<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reporte_semestral_individual extends Model
{
    use HasFactory;
    protected $table = 'reporte_semestral_individual'; // Nombre de la tabla en la base de datos
    protected $fillable = [
        'numero_sesiones_totales',
        'numero_horas_totales',
        'numero_total_asistencias',
        'clave_prep_actividad',
        'clave_prep_situacion',
        'clave_prep_logros',
        'observaciones',
        'totalNumeroSesiones',
        'porcentajeTotalNumeroAsistencias',
        'totalNumeroHorasAtencion',
        'totalNumeroCanalizaciones',
        'actividadTutorialPrep',
        'modalidadPrep',
        'situacionProblematicaPrep',
        'tipoCanalizacionPrep',
        'becaCanalizacionPrep',
        'canalizacionAtendidaPrep',
        'fecha',
        'fecha_ultima_modificacion',
        'id_alumno',
        'id_tutor',
        'periodo',
        'id_generacion',
        'mostrar',
    ];
    // protected $fillable = [
    //     'numero_total_asistencias',
    //     'numero_sesiones_totales',
    //     'numero_horas_totales',
    //     'numero_total_canalizaciones',
    //     'clave_prep_actividad',
    //     'clave_prep_situacion',
    //     'clave_prep_modalidad',
    //     'logros_alcanzados',
    //     'observaciones',
    //     'id_alumno',
    //     'id_tutor',
    //     'id_generacion',
    //     'mostrar'
    // ];
    public $timestamps = false;
}
