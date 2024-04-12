<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutores extends Model
{
    use HasFactory;
    protected $table = 'tutores';
    protected $fillable = ['id_docente','id_coordinador_institucional','mostrar',];

    public $timestamps = false;
}

