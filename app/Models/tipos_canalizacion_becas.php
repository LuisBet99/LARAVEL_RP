<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_canalizacion_becas extends Model
{
    use HasFactory;
    protected $table = 'tipos_canalizacion_becas';
    protected $fillable = ['nombre','abreviacion','clave'];

    public $timestamps = false;

}
