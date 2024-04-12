<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_logros extends Model
{
    use HasFactory;

    protected $table = 'tipos_logros';
    protected $fillable = ['nombre','abreviacion','clave'];

    public $timestamps = false;
}
