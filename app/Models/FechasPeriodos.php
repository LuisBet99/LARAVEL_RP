<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechasPeriodos extends Model
{
    use HasFactory;
    protected $table = 'fechas_periodos';
    protected $fillable = ['fecha_inicio','fecha_final'];

    public $timestamps = false;
}
