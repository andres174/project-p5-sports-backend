<?php

namespace App\Http\Controllers;

use App\Models\AccionJugador;
use Illuminate\Http\Request;

class AccionJugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AccionJugador = AccionJugador::where('estado',1)->get();
        return response()->json($AccionJugador, 200);
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
            'descripcion'=>'required|string|max:255',
        ]);

        $AccionJugador=accionjugador::create([
            'descripcion'=>$validateData['descripcion'],
            'estado'=>1,
        ]);

        return response()->json(['message'=>'AccionJugador registrado'],200);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $AccionJugador=accionjugador::find($id);
        if (is_null($AccionJugador)) {
            return response()->json(['message' => 'accionjugador no encontrado'], 404);
        }
        return response()->json($AccionJugador);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccionJugador $accionJugador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $AJ = accionjugador::find($id);
        if (is_null($AJ)) {
            return response()->json(['message' => 'accionjugador no encontrado.'], 404);
        }
        $validateData = $request->validate([
            'descripcion'=>'required|string|max:255',
        ]);
        $AJ->descripcion = $validateData['descripcion'];
        $AJ->save();
        return response()->json(['message' => 'accionjugador actualizado'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $AccionJugador=accionjugador::find($id);
        if (is_null($AccionJugador)) {
            return response()->json(['message' => 'accionjugador no encontrado'], 404);
        }
        $AccionJugador->estado=0;
        $AccionJugador->save();
        return response()->json(['message'=>'accionjugador eliminado']);
    }
}
