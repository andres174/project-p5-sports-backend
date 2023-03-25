<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configuracion=Configuracion::where('estado',1)
        ->get();
        if (count($configuracion)==0) {
            return response()-> json('no existen configuraciones',404);
        }
        return response()->json($configuracion,200);
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
            'numero_grupos'         =>'required|integer',
            'numero_miembros'       =>'required|integer',
            'minutos_juego'         =>'required|integer',
            'minutos_entre_partidos'=>'required|integer',
            'tarjetas'              =>'required|boolean',
            'ida_y_vuelta'          =>'required|boolean',
        ]);
        $configuracion=Configuracion::create([
            'numero_grupos'         =>$validateData['numero_grupos'],   
            'numero_miembros'       =>$validateData['numero_miembros'],  
            'minutos_juego'         =>$validateData['minutos_juego' ],  
            'minutos_entre_partidos' =>$validateData['minutos_entre_partidos'],  
            'tarjetas'              =>$validateData['tarjetas' ],  
            'ida_y_vuelta'          =>$validateData['ida_y_vuelta'], 
            'estado'                =>1, 
        ]);
        return response()->json(['message'=>'La configuracion se registro exitosamente'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $configuracion=Configuracion::find($id);
        if (is_null($configuracion)) {
            return response()->json(['message' => 'configuracion no encontrada'], 404);
        }
        return response()->json($configuracion);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuracion $configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $configuracion=Configuracion::find($id);
        if (is_null($configuracion)) {
            return response()->json(['message' => 'configuracion no encontrada'], 404);
        }
        $validateData=$request->validate([
            'numero_grupos'         =>'required|integer',
            'numero_miembros'       =>'required|integer',
            'minutos_juego'         =>'required|integer',
            'minutos_entre_partidos'=>'required|integer',
            'tarjetas'              =>'required|boolean',
            'ida_y_vuelta'          =>'required|boolean',
        ]);
        $configuracion->numero_grupos           =$validateData['numero_grupos'];
        $configuracion->numero_miembros         =$validateData['numero_miembros'];
        $configuracion->minutos_juego           =$validateData['minutos_juego'];
        $configuracion->minutos_entre_partidos  =$validateData['minutos_entre_partidos'];
        $configuracion->tarjetas                =$validateData['tarjetas'];
        $configuracion->ida_y_vuelta            =$validateData['ida_y_vuelta'];
        $configuracion->save();
        return response()->json(['message'=>'La configuracion se actualizo exitosamente'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $configuracion=Configuracion::find($id);
        if (is_null($configuracion)) {
            return response()->json(['message' => 'configuracion no encontrada'], 404);
        }
        $configuracion->estado=0;
        $configuracion->save();
        return response()->json(['message'=>'La configuracion se elimino exitosamente'],200);
    }
}
