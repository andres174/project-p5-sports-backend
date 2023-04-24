<?php

namespace App\Http\Controllers;

use App\Models\EventoDisciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoDisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EventoDisciplina $eventoDisciplina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventoDisciplina $eventoDisciplina)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }


    

    public function getEventos(){

        $eventos = DB::table('eventos')
        ->join('usuarios', 'eventos.id_organizador', '=', 'usuarios.id' )
        ->select('eventos.*', 'usuarios.nombre as nombre_org', 'usuarios.apellido as apellido_org', 'usuarios.email')
        ->where('eventos.estado', 1)
        -> get();

        return response()->json($eventos, 200);

    }
    

    public function getDisciplina(){

        $disciplinas = DB::table('disciplinas')
        ->select('disciplinas.nombre as disciplina', //disciplinas
        )
        ->where('disciplinas.estado', 1)
        ->get();

        return response()->json($disciplinas, 200);

    }

    

    public function getConfiguracion(){
        $configuracions = DB::table('configuracions')
        ->join('usuarios', 'configuracions.id_organizador', '=', 'usuarios.id' )
        ->select('configuracions.*', 'usuarios.nombre as nombre_org', 'usuarios.apellido as apellido_org', 'usuarios.email')
        ->where('configuracions.estado', 1)
        ->get();

        return response()->json($configuracions, 200);
    }



    public function MostrarEventoDisciplinas(){

        $EventoDisciplina = DB::table('evento_disciplinas')
        ->join('eventos', 'evento_disciplinas.id_evento', '=', 'eventos.id')
        ->join('disciplinas', 'evento_disciplinas.id_disciplina', '=', 'disciplinas.id')
        ->join('configuracions', 'evento_disciplinas.id_configuracion', '=', 'configuracions.id')
        ->select('evento_disciplinas.id as id_evento_disciplina', /* 'evento_disciplinas.*', */
        'eventos.nombre as nombre_evento', 'eventos.imagen', 'eventos.fecha_inicio'
        , 'eventos.fecha_fin', //evento 
        'disciplinas.nombre as disciplina', //disciplinas
        'configuracions.nombre as nombre_configuracion', 'configuracions.numero_grupos'
        , 'configuracions.numero_miembros', 'configuracions.minutos_juego'
        , 'configuracions.minutos_entre_partidos', 'configuracions.tarjetas'
        , 'configuracions.ida_y_vuelta', 'configuracions.id_organizador as user_config', //configuracion
        
        )
        ->where('evento_disciplinas.estado', 1)
        ->get();

        return response()->json($EventoDisciplina, 200);
    }


    /*public function MostrarOneEventoDisciplinas($id_evento){

        $EventoDisciplina = DB::table('evento_disciplinas')
        ->join('eventos', 'evento_disciplinas.id_evento', '=', 'eventos.id')
        ->join('disciplinas', 'evento_disciplinas.id_disciplina', '=', 'disciplinas.id')
        ->join('configuracions', 'evento_disciplinas.id_configuracion', '=', 'configuracions.id')
        ->select('evento_disciplinas.id as id_evento_disciplina', // 'evento_disciplinas.*', 
        'eventos.nombre as nombre_evento', 'eventos.imagen', 'eventos.fecha_inicio'
        , 'eventos.fecha_fin', //evento 
        'disciplinas.nombre as disciplina', //disciplinas
        'configuracions.nombre as nombre_configuracion', 'configuracions.numero_grupos'
        , 'configuracions.numero_miembros', 'configuracions.minutos_juego'
        , 'configuracions.minutos_entre_partidos', 'configuracions.tarjetas'
        , 'configuracions.ida_y_vuelta', 'configuracions.id_organizador as user_config', //configuracion
        
        )
        ->where('evento_disciplinas.estado', 1)
        ->where('evento_disciplinas.id_evento', $id_evento)
        ->get();

        return response()->json($EventoDisciplina, 200);
    }*/


    
 
}
