<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'numero_grupos',
        'numero_miembros',
        'minutos_juego',
        'minutos_entre_partidos',
        'tarjetas',
        'ida_y_vuelta',
        'estado',
    ];
}
