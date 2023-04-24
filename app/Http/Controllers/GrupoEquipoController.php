<?php

namespace App\Http\Controllers;

use App\Models\GrupoEquipo;
use App\Models\Resultado;
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

    public function tablePositiones($id)
{
    $query = DB::table('evento_disciplinas')
    ->join('grupos', 'evento_disciplinas.id', '=', 'grupos.id_evento_disciplina')
    ->join('partidos', 'grupos.id', '=', 'partidos.id_grupo')
    ->join('resultados', 'partidos.id', '=', 'resultados.id_partido')
    ->where('evento_disciplinas.id', $id)
    ->where('evento_disciplinas.estado', 1)
    ->where('grupos.estado', 1)
    ->where('partidos.isPlay', 1)
    ->where('resultados.estado', 1)
    ->select('resultados.id_equipo_disciplina')
    ->get();

    
    $ids_equipo_disciplina = [];
    $Equipos = [];
    $Pts = [];
    $pj = [];
    $g = [];
    $e = [];
    $p = [];
    $gf = [];
    $gc = [];
    $gd = [];
    $data = array();
    $aux = [];

    foreach ($query as $key => $value) {
        $ids_equipo_disciplina[$key] = $value->id_equipo_disciplina;
    }

    $ids_equipo_disciplina = array_unique($ids_equipo_disciplina);

    foreach ($ids_equipo_disciplina as $k => $v) {

        $Equipos[$k] = DB::table('equipo_disciplinas')
            ->join('equipos', 'equipo_disciplinas.id_equipo', '=', 'equipos.id')
            ->where('equipo_disciplinas.id', $ids_equipo_disciplina[$k])
            ->first();
            
        $Pts[$k] = Resultado::where('id_equipo_disciplina', $v)->sum('puntos');
        $Pts[$k] = Resultado::where('id_equipo_disciplina', $v)->sum('puntos');
        $pj[$k] = Resultado::where('id_equipo_disciplina', $v)->count();
        
        $g[$k] = Resultado::where('id_equipo_disciplina', $v)
        ->where('puntos', 3)
        ->count();

        $e[$k] = Resultado::where('id_equipo_disciplina', $v)
            ->where('puntos', 1)
            ->count();

        $p[$k] = Resultado::where('id_equipo_disciplina', $v)
            ->where('puntos', 0)
            ->count();
       

        $gf[$k] = Resultado::where('id_equipo_disciplina', $v)->sum('goles_favor');
        $gc[$k] = Resultado::where('id_equipo_disciplina', $v)->sum('goles_contra');
        $gd[$k] = $gf[$k] - $gc[$k];

        array_push(
            $data,
            [
                'Equipos' => $Equipos[$k],
                'Pts' => $Pts[$k],
                'pj' => $pj[$k],
                'g' => $g[$k],
                'e' => $e[$k],
                'p' => $p[$k],
                'gf' => $gf[$k],
                'gc' => $gc[$k],
                'gd' => $gd[$k]
            ]
        );
    }


    $result = $this->ordenarTablaPosiciones($data);

    // $result = $this->ordenarTablaPosiciones($data);

    return response()->json($result);
}

function ordenarTablaPosiciones($tablaPosiciones) {
    // Ordenar por puntos
    usort($tablaPosiciones, function($a, $b) {
        if ($a['Pts'] == $b['Pts']) {
            // Si hay empate en puntos, ordenar por diferencia de goles
            return $b['gd'] - $a['gd'];
        } else {
            return $b['Pts'] - $a['Pts'];
        }
    });
    return $tablaPosiciones;
}





}
