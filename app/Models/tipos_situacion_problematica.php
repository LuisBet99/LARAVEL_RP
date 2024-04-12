<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_situacion_problematica extends Model
{
    use HasFactory;
    protected $table = 'tipos_situacion_problematica';
    protected $fillable = ['nombre','abreviacion','clave'];

    public $timestamps = false;

}
