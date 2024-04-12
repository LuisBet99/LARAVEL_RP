<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_modalidad extends Model
{
    use HasFactory;
    protected $table = 'tipos_modalidad';
    protected $fillable = ['nombre','abreviacion','clave'];

    public $timestamps = false;

}
