<?php

namespace App\Http\Controllers;

use App\Models\GrupoEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupoEquipoController extends Controller
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
    public function show(GrupoEquipo $grupoEquipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GrupoEquipo $grupoEquipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GrupoEquipo $grupoEquipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GrupoEquipo $grupoEquipo)
    {
        //
    }

    public function getGruposParaTablaPosicion($id_evento_disciplina){

        $respuesta = Array();

        $grupo = DB::table('grupos')
        ->select('grupos.*')
        ->where('grupos.id_evento_disciplina', $id_evento_disciplina)
        ->where('grupos.estado', 1)
        ->get();
        
        array_push($respuesta, ['grupos' => $grupo]);

        foreach ($grupo as $g) {
            
            $equipo_temp = DB::table('grupo_equipos')
            ->select('grupo_equipos.*')
            ->where('grupo_equipos.id_grupo', $g->id)
            ->where('grupo_equipos.estado', 1)
            ->get();

            array_push($respuesta, ['Equipos_'.$g->nombre_grupo => $equipo_temp]);

        }

        return response()->json($respuesta, 200);

    }

}
