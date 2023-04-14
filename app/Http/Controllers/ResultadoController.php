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
        $resultado = resultado::where('estado',1)->get();
        return response()->json($resultado, 200);
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
        $validateData=$request->validate([
            'puntos'         =>'required|integer',
            'goles_favor'       =>'required|integer',
            'goles_contra'         =>'required|integer',
            'id_equipo_disciplina'  =>'required',
            'id_partido'   =>'required',
        ]);
        $resultado=resultado::create([
            'puntos' =>$validateData['puntos'],   
            'goles_favor'=>$validateData['goles_favor'],  
            'goles_contra' =>$validateData['goles_contra' ],  
            'id_equipo_disciplina' =>$validateData['id_equipo_disciplina'],  
            'id_partido'=>$validateData['id_partido' ],  
            'estado'=>1, 
        ]);
        return response()->json(['message'=>'Los Resultados se registro exitosamente'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $resultado=resultado::find($id);
        if (is_null($resultado)) {
            return response()->json(['message' => 'resultado no encontrado'], 404);
        }
        return response()->json($resultado);
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
    public function update(Request $request,  $id)
    {
        $resultado=resultado::find($id);
        if (is_null($resultado)) {
            return response()->json(['message' => 'resultado no encontrada'], 404);
        }
        $validateData=$request->validate([
            'puntos'         =>'required|integer',
            'goles_favor'       =>'required|integer',
            'goles_contra'         =>'required|integer',
            'id_equipo_disciplina'  =>'required',
            'id_partido'   =>'required',
        ]);
        $resultado->puntos           =$validateData['puntos'];
        $resultado->goles_favor         =$validateData['goles_favor'];
        $resultado->goles_contra           =$validateData['goles_contra'];
        $resultado->id_equipo_disciplina  =$validateData['id_equipo_disciplina'];
        $resultado->id_partido                =$validateData['id_partido'];
        $resultado->save();
        return response()->json(['message'=>'Los Resultado se actualizaron exitosamente'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $resultado= resultado::find($id);
        if (is_null($resultado)) {
           return response()->json(['message'=> 'resultado no encontrado'], 404);
        }
        $resultado->estado=0;
        $resultado->save();
        return response()->json(['message' => 'resultado actualizado'], 201);

    }


    public function deleteSelectResultados(request $request){
        $aux=explode(',',$request->ids);
        for($i=0; $i<count($aux); $i++){
            $resultados=resultados::find($aux[$i]);
            $resultados->estado=0;
            $resultados->save();
        }
        return response()->json(['message'=>'Las resultados se eliminaron exitosamente'],200);
    }


    public function tabla_posiciones()
    {
        $resultado = DB::table('resultados')
        ->join('equipo_disciplinas','resultados.id_equipo_disciplina','=','equipo_disciplinas.id')
        ->join('partidos','resultados.id_partido','=','partidos.id')
        ->select('resultados.*','partidos.equipo_1','partidos.equipo_2','partidos.lugar','equipo_disciplinas.id_equipo','equipo_disciplinas.id_evento_disciplina',)
        ->where('equipo_disciplinas.estado',1)
        ->where('partidos.estado',1)
        ->get();
        return response()->json($resultado, 200);
    }

    public function tabla_posicion(){
        $resultado = DB::table('resultados')
        ->join('equipo_disciplinas', 'resultados.id_equipo_disciplina', '=', 'equipo_disciplinas.id')
        ->join('partidos', 'resultados.id_partido', '=', 'partidos.id')
        ->select('resultados.*' )
        ->where('resultados.estado', 1)
        ->get();
        return response()->json($resultado, 200);
    }

   

/* Metodo para la tabla de pociciones */
    public function tablePosition($id)
    {
        $query = DB::table('grupos')
        ->join('partidos', 'grupos.id', '=', 'partidos.id_grupo')
        ->join('resultados', 'partidos.id', '=', 'resultados.id_partido')
        ->where('grupos.id', $id)
        ->where('grupos.estado', 1)
        ->where('partidos.isPlay', 1)
        ->where('resultados.estado', 1)
        ->select('resultados.id_equipo_disciplina')
        ->get();

        $ids_equipo_disciplina = [];
        $Equipos = [];
        $Pts = [];
        $PJ = [];
        $G = [];
        $E = [];
        $P = [];
        $GF = [];
        $GC = [];
        $GD = [];
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
            $PJ[$k] = Resultado::where('id_equipo_disciplina', $v)->count();

            $E[$k] = Resultado::where('id_equipo_disciplina', $v)
                ->where('puntos', 1)
                ->count();
            $P[$k] = Resultado::where('id_equipo_disciplina', $v)
                ->where('puntos', 0)
                ->count();
            $G[$k] = Resultado::where('id_equipo_disciplina', $v)
                ->where('puntos', 3)
                ->count();

            $GF[$k] = Resultado::where('id_equipo_disciplina', $v)->sum('goles_favor');
            $GC[$k] = Resultado::where('id_equipo_disciplina', $v)->sum('goles_contra');
            $GD[$k] = $GF[$k] - $GC[$k];

            array_push(
                $data,
                [
                    'Equipos' => $Equipos[$k],
                    'Pts' => $Pts[$k],
                    'PJ' => $PJ[$k],
                    'G' => $G[$k],
                    'E' => $E[$k],
                    'P' => $P[$k],
                    'GF' => $GF[$k],
                    'GC' => $GC[$k],
                    'GD' => $GD[$k]
                ]
            );
        }

  
        $result = $this->burbuja($data);
        $result = $this->burbuja2($data);

        return response()->json($result);
    }

    function burbuja($arreglo)
    {
        $longitud = count($arreglo);
        for ($i = 0; $i < $longitud; $i++) {
            for ($j = 0; $j < $longitud - 1; $j++) {
                if ($arreglo[$j]['Pts'] < $arreglo[$j + 1]['Pts']) {
                    $temporal = $arreglo[$j];
                    $arreglo[$j] = $arreglo[$j + 1];
                    $arreglo[$j + 1] = $temporal;
                }
            }
        }
        return $arreglo;
    }

    function burbuja2($arreglo)
   {
        $longitud = count($arreglo);
        for ($i = 0; $i < $longitud; $i++) {
            for ($j = 0; $j < $longitud - 1; $j++) {
                if ($arreglo[$j]['GD'] < $arreglo[$j + 1]['GD']) {
                    $temporal = $arreglo[$j];
                    $arreglo[$j] = $arreglo[$j + 1];
                    $arreglo[$j + 1] = $temporal;
                }
            }
        }
        return $arreglo;
    } 


}
