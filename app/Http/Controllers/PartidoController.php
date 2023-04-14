<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\EventoDisciplina;
use App\Models\Configuracion;
use App\Models\GrupoEquipo;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class PartidoController extends Controller
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
    public function show(Partido $partido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partido $partido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partido $partido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partido $partido)
    {
        //
    }

    public function crearPartidos($id){
        $fecha_inicio = "2023-04-07 09:00:00";
        $gruposArray=Array();
        $gruposPartidos=Array();
        //CONSULTA DE INFORMACION
        $eventoDisciplina=EventoDisciplina::find($id);
        $configuracion=Configuracion::find($eventoDisciplina->id);
        $grupo=Grupo::where('id_evento_disciplina',$id)->get();
        foreach ($grupo as $key => $value) {
            $grupoEquipo=GrupoEquipo::where('id_grupo',$value->id)->get();
            array_push($gruposArray,$grupoEquipo);
        }
        

        for ($i=0; $i<count($gruposArray) ; $i++) { 
            $partidos=$this->crearEncuentros($gruposArray[$i]);
            array_push($gruposPartidos,['grupo'=>$i,'partidos'=>$partidos]);
        }
        
        return response()-> json($gruposPartidos);

       // $aumentoTiempo=$configuracion->minutos_juego + $configuracion->minutos_entre_partidos;
        //CREACION DE RANNGO DE TIEMPO SUJETO A POSIBLE CAMBIO DONDE EL VALOR SE INGRESE Y NO SE ESTABLEZCA POR DEFECTO
        // $inicio = Carbon::now()->setTime(8, 0, 0);#Hora de inicio
        // $fecha_inicio = "2023-04-08 09:00:00";
        // $fin = Carbon::now()->setTime(22, 0, 0);#Hora de culminacion
        // $fecha_hora_carbon = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_inicio);//crear una instamcia para la agregar los minutos partiendo de la fecha y hora del primer partido
        //  do {
        //     Partido::create([
        //         'equipo_1'=>1,
        //         'equipo_2'=>2,
        //         'fecha_hora'=>$fecha_hora_carbon,
        //         'isPlay'=>1,
        //         'lugar'=>'lejo de aqui cerca de alla',
        //         'lat'=>'0.000000',
        //         'lng'=>'-0.000000',
        //         'id_grupo'=>1
        //     ]);
        //     $fecha_hora_carbon->addMinutes($aumentoTiempo);
        //  } while ($fecha_hora_carbon <= $fin);
        

    }

    public function crearEncuentros($grupo)
    {
        foreach ($grupo as $key1 => $equipo1) {
            foreach (array_slice($grupo, $key1 + 1) as $key2 => $equipo2) {
                $partidos[] = ['local' => $equipo1, 'visitante' => $equipo2];
            }
        }
        return $partidos;
    }
}
