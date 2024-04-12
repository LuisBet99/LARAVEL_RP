<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechasPeriodosAsignadas extends Model
{
    use HasFactory;
    protected $table = 'fechas_periodos_asignadas';
    protected $fillable = ['id_periodo','id_fecha_primera_sesion','id_generacion'];

    public $timestamps = false;
}
