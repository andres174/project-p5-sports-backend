<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePartido extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_partido',
        'id_jugador',
        'id_accion_jugador',
        'estado',
    ];
}
