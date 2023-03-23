<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'foto_perfil',
        'estado',
        'id_tipo_usuario',
    ];
}
