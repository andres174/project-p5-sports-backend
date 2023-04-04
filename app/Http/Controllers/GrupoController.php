<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Configuracion;
use App\Models\GrupoEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    
    public function index()
    {
        //
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
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

    
    public function destroy($id)
    {
        // hay que ver como hago esto
    }

    // no uso las routas resource por ahora

    public function guardarGruposGenerados(Request $request){

        //recibimos el id_evento_disciplina y los id de los equipos (id_equipo_disciplina creo)

        $req_validada = $request->validate([
            'id_evento_disciplina' => 'required',
            'equipos' => 'required' //aqui va un array con los equipos de esa disciplina
        ]);

        $equipos_miembros = explode(',', $req_validada['equipos']); //se descomponen los equipos en un array funcional

        $random_equipos_miembros = $this->equiposRandom($equipos_miembros);
        
        $configuracion = $this->getConfiguracion($req_validada['id_evento_disciplina']);

        if(count($configuracion) == 0){
            return response()->json(['message' => 'No hay configuraciones para esta disiplina. No se pueden crear los grupos.'], 200);
        }

        $numero_grupos = $configuracion[0]->numero_grupos;
        $numero_miembros = $configuracion[0]->numero_miembros;

        $this->generarGrupos($numero_grupos, $numero_miembros, $random_equipos_miembros, $req_validada['id_evento_disciplina']);

        return response()->json(['message' => 'Grupos generados automáticamente']);

    }

    //funcion para ordenar los equipos de forma aleatoria
    private function equiposRandom($eq){

        for ($i=0; $i < count($eq); $i++) { 
            $posicionRandom = rand(0, count($eq) - 1);
            $aux = $eq[$i];
            $eq[$i] = $eq[$posicionRandom];
            $eq[$posicionRandom] = $aux;
            //ordenamos de forma aleatoria los equipos   
        }

        return $eq;

    }
     

    // función para obtener la configuración

    private function getConfiguracion($id_evento_disciplina){
        $config = DB::table('evento_disciplinas')
        ->join('configuracions','evento_disciplinas.id_configuracion','=','configuracions.id')
        ->select('configuracions.*','evento_disciplinas.id_configuracion')
        ->where('evento_disciplinas.id', $id_evento_disciplina)
        ->get();

        //faltan pruebas a fondo
        
        //return real
        return $config;
        //return para pruebas
        /* return response()->json($config, 200); */
    }



    //En esta función se empiezan a crear los grupos como tal
    /* Obtiene la info de los miembros ademas va guardando los arrays y crea los nombres y los grupos como tal */
    private function generarGrupos($numero_grupos, $numero_miembros, $random_equipos_miembros, $id_evento_disciplina){

        $inicio = 0;
        $final = $numero_miembros;

        for ($i=0; $i < $numero_grupos; $i++) { 
            
            $equipos = $this->obtenerEquipos($random_equipos_miembros, $inicio, $final); 

            $string_equipos = implode(",", $equipos);

            $nombre_grupo = $this->obtenerNombreGrupo($id_evento_disciplina); 

            $this->crearGrupo($string_equipos, ($i + 1)." ".$nombre_grupo, $id_evento_disciplina); //falta

            $inicio = $final;
            $final = $final + $numero_miembros;

        }

    }



    //solo dios sabe que hace esta función
    //Creo que hace algo con los equipos

    //FALTAN PRUEBAS
    private function obtenerEquipos($random_equipos_miembros, $inicio, $final){

        $new_equipos = [];

        for ($i=0; $i < count($random_equipos_miembros); $i++) { 
            if ($i >= $inicio && $i < $final) {
                $new_equipos[$i] = $random_equipos_miembros[$i];
            }
        }

        return $new_equipos;
    }



    //obtiene el nombre de la disciplina para poder crear el grupo de una forma mas comoda


    //FALTAN PRUEBAS
    public function obtenerNombreGrupo($id_evento_disciplina){

        $nombreC = DB::table('disciplinas')
        ->join('evento_disciplinas', 'disciplinas.id', '=', 'evento_disciplinas.id_disciplina')
        ->select('disciplinas.nombre')
        ->where('evento_disciplinas.id', $id_evento_disciplina)
        ->get();

        return $nombreC[0]->nombre;

    }


    //como tal esta es la funcion que crea los grupos en la base de datos
    private function crearGrupo($string_equipos, $nombre_grupo, $id_evento_disciplina){

        $grupo = Grupo::create([
            'nombre_grupo' => "GRUPO {$nombre_grupo}",
            'id_evento_disciplina' => $id_evento_disciplina,
            'estado' => 1
        ]);

        $equipos_crear = explode(',', $string_equipos);

        foreach ($equipos_crear as $e) {
            GrupoEquipo::create([
                'id_equipo' => $e,
                'id_grupo' => $grupo->id,
                'estado' => 1
            ]);
        }
        
    }

    //idea

    public function previsualizarGrupos(){

        //devolver los grupos antes de crearlos como tal en la base de datos

    }


}
