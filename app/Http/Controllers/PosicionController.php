<?php

namespace App\Http\Controllers;

use App\Models\Posicion;
use Illuminate\Http\Request;

class PosicionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posiciones = Posicion::where('estado', 1)->get();
        return response()->json($posiciones, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        $posicion = Posicion::create([
            'descripcion' => $validateData['descripcion'],
            'estado' => 1,
        ]);
        return response()->json(['message' => 'Posición creada correctamente.'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $posicion = Posicion::find($id);

        if (is_null($posicion)) {
            return response()->json(['message' => 'Posición no encontrada.'], 404);
        }

        return response()->json($posicion, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $posicion = Posicion::find($id);

        if (is_null($posicion)) {
            return response()->json(['message' => 'Posición no encontrada.'], 404);
        }

        $validateData = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $posicion->descripcion = $validateData['descripcion'];
        $posicion->save();
        return response()->json(['message' => 'Posición actualizada correctamente.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $posicion = Posicion::find($id);

        if (is_null($posicion)) {
            return response()->json(['message' => 'Posición no encontrada.'], 404);
        }

        $posicion->estado = 0;
        $posicion->save();

        return response()->json(['message' => 'Posición eliminada correctamente.'], 200);
    }
}
