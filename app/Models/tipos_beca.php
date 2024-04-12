<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_beca extends Model
{
    use HasFactory;
    protected $table = 'tipos_beca';
    protected $fillable = ['nombre','abreviacion','clave'];

    public $timestamps = false;

}
