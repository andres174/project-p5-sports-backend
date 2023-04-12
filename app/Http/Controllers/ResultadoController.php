<?php

namespace App\Http\Controllers;

use App\Models\Resultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class ResultadoController extends Controller
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
    public function show(Resultado $resultado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resultado $resultado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resultado $resultado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resultado $resultado)
    {
        //
    }

   /*  public function tablePosition($id)
    {
        $query = DB::table('groups')
            ->join('versus', 'groups.id', '=', 'versus.groupId')
            ->join('results', 'versus.id', '=', 'results.versusId')

            ->where('groups.id', $id)
            ->where('groups.state', true)
            ->where('versus.state', true)
            ->where('results.state', true)
            ->select('results.idEventTeam')
            ->get();

        $idsEventTeam = [];
        $team = [];
        $ptos = [];
        $pj = [];
        $pg = [];
        $pe = [];
        $pp = [];
        $gf = [];
        $gc = [];
        $g = [];
        $data = array();
        $aux = [];

        foreach ($query as $key => $value) {
            $idsEventTeam[$key] = $value->idEventTeam;
        }

        $idsEventTeam = array_unique($idsEventTeam);

        foreach ($idsEventTeam as $k => $v) {

            $team[$k] = DB::table('events_teams')
                ->join('teams', 'events_teams.idTeam', '=', 'teams.id')
                ->where('events_teams.id', $idsEventTeam[$k])
                ->first();

            $ptos[$k] = Results::where('idEventTeam', $v)->sum('points');
            $pj[$k] = Results::where('idEventTeam', $v)->count();

            $pe[$k] = Results::where('idEventTeam', $v)
                ->where('points', 1)
                ->count();
            $pp[$k] = Results::where('idEventTeam', $v)
                ->where('points', 0)
                ->count();
            $pg[$k] = Results::where('idEventTeam', $v)
                ->where('points', 3)
                ->count();

            $gf[$k] = Results::where('idEventTeam', $v)->sum('goals_for');
            $gc[$k] = Results::where('idEventTeam', $v)->sum('goals_against');
            $g[$k] = $gf[$k] - $gc[$k];

            array_push(
                $data,
                [
                    'team' => $team[$k],
                    'ptos' => $ptos[$k],
                    'pj' => $pj[$k],
                    'pg' => $pg[$k],
                    'pe' => $pe[$k],
                    'pp' => $pp[$k],
                    'gf' => $gf[$k],
                    'gc' => $gc[$k],
                    'g' => $g[$k]
                ]
            );
        }

  
        $result = $this->burbuja($data);
        $result = $this->burbuja2($data);

        return response()->json($result);
    } */

/*     function burbuja($arreglo)
    {
        $longitud = count($arreglo);
        for ($i = 0; $i < $longitud; $i++) {
            for ($j = 0; $j < $longitud - 1; $j++) {
                if ($arreglo[$j]['ptos'] < $arreglo[$j + 1]['ptos']) {
                    $temporal = $arreglo[$j];
                    $arreglo[$j] = $arreglo[$j + 1];
                    $arreglo[$j + 1] = $temporal;
                }
            }
        }
        return $arreglo;
    } */

 /*    function burbuja2($arreglo)
   {
        $longitud = count($arreglo);
        for ($i = 0; $i < $longitud; $i++) {
            for ($j = 0; $j < $longitud - 1; $j++) {
                if ($arreglo[$j]['g'] < $arreglo[$j + 1]['g']) {
                    $temporal = $arreglo[$j];
                    $arreglo[$j] = $arreglo[$j + 1];
                    $arreglo[$j + 1] = $temporal;
                }
            }
        }
        return $arreglo;
    }  */

    public function tabla_posicion()
    {
        $producto = DB::table('productos')
        ->join('marcas','productos.id_marca','=','marcas.id')
        ->join('tipo_pesos','productos.id_tipo_peso','=','tipo_pesos.id')
        ->join('categorias','productos.id_categoria','=','categorias.id')
        ->select('productos.*','marcas.descripcion as marca','tipo_pesos.descripcion as tipo_peso','categorias.descripcion as categoria')
        ->where('productos.estado',1)
        ->where('marcas.estado',1)
        ->where('tipo_pesos.estado',1)
        ->where('categorias.estado',1)
        ->get();
        return response()->json($producto, 200);
    }
}
