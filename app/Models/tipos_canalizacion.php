<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_canalizacion extends Model
{
    use HasFactory;
    protected $table = 'tipos_canalizacion';
    protected $fillable = ['nombre','abreviacion','clave'];

    public $timestamps = false;

}
