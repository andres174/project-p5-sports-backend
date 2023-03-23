<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoDisciplina extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_disciplina',
        'id_evento',
        'id_configuracion',
        'estado'
    ];
}
