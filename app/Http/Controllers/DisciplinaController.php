<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{

    public function index()
    {
        //
        $disciplina = Disciplina::where('estado', 1)->get();
        return response()->json($disciplina, 200);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
        $validateData = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $disciplinaStore = Disciplina::create([
            'nombre' => $validateData['nombre'],
            'estado' => 1,
        ]);

        return response()->json(['message' => 'Disciplina registrada correctamente'], 200);

    }


    public function show($id)
    {
        //
        $disciplinaShow = Disciplina::find($id);

        if (is_null($disciplinaShow)) {
            return response()->json(['message' => 'Disciplina no encontrada.'], 404);
        }

        return response()->json($disciplinaShow, 200);
    }


    public function edit(Disciplina $disciplina)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
        $disciplinaUpdate = Disciplina::find($id);

        if (is_null($disciplinaUpdate)) {
            return response()->json(['message' => 'Disciplina no encontrada.'], 404);
        }

        $validateData = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $disciplinaUpdate->nombre = $validateData['nombre'];
        $disciplinaUpdate->save();

        return response()->json(['message' => 'Disciplina actualizada correctamente'], 200);

    }
    
    public function destroy($id)
    {
        //
        $disciplinaDestroy = Disciplina::find($id);

        if (is_null($disciplinaDestroy)) {
            return response()->json(['message' => 'Disciplina no encontrada.'], 404);
        }



        $disciplinaDestroy->estado = 0;
        $disciplinaDestroy->save();

        return response()->json(['message' => 'Disciplina eliminada correctamente'], 200);

    }
}