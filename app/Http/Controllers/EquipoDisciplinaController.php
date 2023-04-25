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

        $equipo = DB::table('equipos')->where('estado', 1)->find($validatedData['id_equipo']);
        if (is_null($equipo))
            return response()->json(['message' => 'No existe el Equipo'], 404);

        $configuracion = DB::table('evento_disciplinas as evd')
            ->join('configuracions as c', 'c.id', 'evd.id_configuracion')
            ->where('evd.id', $validatedData['id_evento_disciplina'])
            ->first('c.*');

        $equipoQuery = DB::table('equipos as eq')
            ->join('equipo_disciplinas as eqd', 'eq.id', 'eqd.id_equipo')
            ->join('evento_disciplinas as evd', 'evd.id', 'eqd.id_evento_disciplina')
            ->where('eqd.id_evento_disciplina', $validatedData['id_evento_disciplina'])
            ->where('eq.estado', 1)
            ->where('eqd.estado', 1)
            ->where('evd.estado', 1);

        $maxNumberEquipos = $configuracion->numero_miembros * $configuracion->numero_grupos;
        $numberEquipos = $equipoQuery->count();

        if ($numberEquipos >= $maxNumberEquipos)
            return response()->json(['message' => 'Se alzanzó el número máximo de Equipos en esta Disciplina'], 400);

        $equipoRepetido = $equipoQuery
            ->where('eq.id', $validatedData['id_equipo'])
            ->exists();

        if ($equipoRepetido)
            return response()->json(['message' => 'El Equipo ya existe en la Disciplina'], 400);

        $equipoDisciplina = EquipoDisciplina::create([
            ...$validatedData,
            'estado' => 1
        ]);

        return response()->json(['message' => 'Equipo añadido a la Disciplina con éxito'], 200);
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

    public function deleteSelectedEquipoDisciplinas(Request $request)
    {
        $validatedData = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        EquipoDisciplina::whereIn('id', $validatedData['ids'])->update(['estado' => 0]);

        return response()->json(['message' => 'Equipos eliminados de la Disciplina con éxito'], 200);
    }

    public function getEquipoDisciplinasByDisciplina(string $id_evento_disciplina)
    {
        $query = DB::table('equipo_disciplinas as eqd')
            ->join('equipos as eq', 'eqd.id_equipo', 'eq.id')
            ->where('eqd.id_evento_disciplina', $id_evento_disciplina)
            ->where('eqd.estado', 1)
            ->where('eq.estado', 1);

        $equipos = $query->get('eq.*')->toArray();
        $equipoDisciplinasIds = $query->pluck('eqd.id')->toArray();

        $equipoDisciplinas = array_map(fn ($edId, $eq) => [
            'id' => $edId,
            'id_evento_disciplina' => (int)$id_evento_disciplina,
            'equipo' => $eq
        ], $equipoDisciplinasIds, $equipos);

        return response()->json($equipoDisciplinas, 200);
    }

    public function getEquiposByDisciplina(string $id_evento_disciplina)
    {
        $equipos = DB::table('equipo_disciplinas as eqd')
            ->join('equipos as eq', 'eqd.id_equipo', 'eq.id')
            ->select('eq.*')
            ->where('eqd.id_evento_disciplina', $id_evento_disciplina)
            ->where('eqd.estado', 1)
            ->where('eq.estado', 1)
            ->get();

        return response()->json($equipos, 200);
    }

    public function getEquiposToAddByDisciplina(string $id_evento_disciplina)
    {
        $equiposIdInDisciplina = DB::table('equipo_disciplinas as eqd')
            ->join('equipos as eq', 'eqd.id_equipo', 'eq.id')
            ->where('eqd.id_evento_disciplina', $id_evento_disciplina)
            ->where('eqd.estado', 1)
            ->where('eq.estado', 1)
            ->pluck('eq.id');

        $equiposToAdd = DB::table('equipos')
            ->whereNotIn('id', $equiposIdInDisciplina)
            ->get();

        return response()->json($equiposToAdd, 200);
    }
}
