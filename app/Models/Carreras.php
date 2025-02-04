<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carreras extends Model
{
    use HasFactory;
    protected $table = 'carreras';
    protected $fillable = ['nombre','mostrar'];

    public $timestamps = false;
}
