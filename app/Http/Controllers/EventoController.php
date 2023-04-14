<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evento = Evento::where('estado',1) ->get();
        if (count($evento)==0) {
            return response()-> json('no existen evento',404);
        }
        return response()->json($evento,200);
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
            'nombre'=>'required|string|max:255',
            'imagen' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'fecha_inicio'=>'required',
            'fecha_fin'=>'required',
            'id_organizador' =>'required',

        ]);
        //imagen
        $img = $request->file('imagen');
        $valiData['imagen'] =  time().'.'.$img->getClientOriginalExtension();


        $evento = Evento::create([
            'nombre'=>$validateData['nombre'],
            'imagen'=>$valiData['imagen'],
            'fecha_inicio'=>$validateData['fecha_inicio'],
            'fecha_fin'=>$validateData['fecha_fin'],
            'id_organizador'=>$validateData['id_organizador'],
            'estado'=>1,
        ]);

        $request->file('imagen')->storeAs("public/imagen/evento/{$evento->id}", $valiData['imagen']);

        return response()->json(['message'=>'Evento registrado'],200);
      // return response()->json($request,200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $evento=DB::table('eventos')
        ->join('usuarios','eventos.id_organizador','=','usuarios.id')
        ->select('eventos.*')
        ->where('eventos.estado',1)
        ->where('eventos.id_organizador',$id)
        ->orWhere('eventos.id_organizador',1)
        ->get();
        if (count($evento)== 0) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }
        return response()->json($evento);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::find($id);
        if (is_null($evento)) {
            return response()->json(['message' => 'Evento no encontrado.'], 404);
        }
        $validateData = $request->validate([
            'nombre'=>'required|string|max:255',
            'fecha_inicio'=>'required|date',
            'fecha_fin'=>'required|date',
            'id_organizador' =>'required',
            

        ]);
        $evento->nombre = $validateData['nombre'];
        $evento->fecha_inicio = $validateData['fecha_inicio'];
        $evento->fecha_fin = $validateData['fecha_fin'];
        $evento->id_organizador = $validateData['id_organizador'];
        $evento->save();
        return response()->json(['message' => 'Evento actualizado'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evento = Evento::find($id);
        if (is_null($evento)) {
            return response()->json(['message' => 'evento no encontrado'], 404);
        }
        $evento->estado=0;
        $evento->save();
        return response()->json(['message'=>'El evento ha sido eliminado']);
    }

    public function EditarImagenEvento(Request $request, $id ){

        $evento = Evento::find($id);
        if (is_null($evento)) {
            return response()->json(['message' => 'Imagen no encontrada.'], 404);
        }
        $validateData = $request->validate([
            'imagen' => 'required|mimes:jpeg,bmp,png',
        ]);
        $img=$request->file('imagen');
        $validateData['imagen'] = time().'.'.$img->getClientOriginalExtension();
        $request->file('imagen')->storeAs("public/imagen/evento/{$evento->id}", $validateData['imagen']);
        $evento->imagen=$validateData['imagen'];
        $evento->save();
        return response()->json(['message' => 'imagen del evento actualizada'], 201);
    }

    public function deleteSelectEvento(request $request){
       
        $aux=explode(',',$request->ids);
        for($i=0; $i<count($aux); $i++){
            $evento = Evento::find($aux[$i]);
            $evento->estado=0;
            $evento->save();
        }
        return response()->json(['message'=>'Los eventos se eliminaron correctamente'],200);

    }
}
