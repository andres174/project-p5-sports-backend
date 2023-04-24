<?php

namespace App\Http\Controllers;

use App\Models\EquipoDisciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipoDisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipoDisciplina = EquipoDisciplina::where('estado', 1)->get();
        return response()->json($equipoDisciplina, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_equipo' => 'required|integer',
            'id_evento_disciplina' => 'required|integer'
        ]);

        $equipoRepetido = DB::table('equipos as eq')
            ->join('equipo_disciplinas as eqd', 'eq.id', 'eqd.id_equipo')
            ->join('evento_disciplinas as evd', 'evd.id', 'eqd.id_evento_disciplina')
            ->where('eqd.id_evento_disciplina', $validatedData['id_evento_disciplina'])
            ->where('eq.id', $validatedData['id_equipo'])
            ->where([['eq.estado', 1], ['eqd.estado', 1], ['evd.estado', 1]])
            ->first();

        if ($equipoRepetido)
            return response()->json(['message' => 'El Equipo ya existe en la Disciplina'], 400);

        $equipoDisciplina = EquipoDisciplina::create([
            ...$validatedData,
            'estado' => 1
        ]);

        return response()->json(['message' => 'Equipo añadido a la Disciplina correctamente'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipoDisciplina = EquipoDisciplina::where('estado', 1)->find($id);

        if (is_null($equipoDisciplina))
            return response()->json(['message' => 'Equipo Disciplina no encontrado'], 404);

        return response()->json($equipoDisciplina, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json(null, 501);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipoDisciplina = EquipoDisciplina::where('estado', 1)->find($id);

        if (is_null($equipoDisciplina))
            return response()->json(['message' => 'Equipo Disciplina no encontrado'], 404);

        $equipoDisciplina->estado = 0;
        $equipoDisciplina->save();

        return response()->json(['message' => 'Equipo Disciplina eliminado con éxito'], 200);
    }

    public function getEquiposByDisciplina(string $id_evento_disciplina)
    {
        $equipos = DB::table('equipo_disciplinas as eqd')
            ->join('equipos as eq', 'eqd.id_equipo', 'eq.id')
            ->select('eq.*')
            ->where('eqd.id_evento_disciplina', $id_evento_disciplina)
            ->where(['eqd.estado', 1], ['eq.estado', 1])
            ->get();

        return response()->json($equipos, 200);
    }
}
