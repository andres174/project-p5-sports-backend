<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JugadorEquipo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_jugador',
        'id_equipo',
        'estado',
    ];
}
