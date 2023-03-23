<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'equipo_1',
        'equipo_2',
        'fecha_hora',
        'isPlay',
        'lugar',
        'lat',
        'lng',
        'id_grupo',
    ];
}
