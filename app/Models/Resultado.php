<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'puntos',
        'goles_favor',
        'goles_contra',
        'id_equipo_disciplina',
        'id_partido',
        'estado',
    ];
}
