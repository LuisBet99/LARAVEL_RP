<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_actividad_tutorial extends Model
{
    use HasFactory;


    protected $table = 'tipos_actividad_tutorial';
    protected $fillable = ['nombre','abreviacion','clave'];

    public $timestamps = false;

}
