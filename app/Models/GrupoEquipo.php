<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoEquipo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_equipo',
        'id_grupo',
        'estado',
    ];
}
