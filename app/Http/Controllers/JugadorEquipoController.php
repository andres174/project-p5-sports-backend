<?php

namespace App\Http\Controllers;

use App\Models\JugadorEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JugadorEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jugadorEquipos = JugadorEquipo::where('estado', 1)->get();
        return response()->json($jugadorEquipos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_jugador' => 'required|integer',
            'id_equipo_disciplina' => 'required|integer',
            'id_posicion' => 'required|integer',
            'numero' => 'required|integer',
        ]);

        $idEventoDisciplina = $this->getIdEventoDisciplina($validatedData['id_equipo_disciplina']);

        if (is_null($idEventoDisciplina))
            return response()->json(['message' => 'El Equipo Disciplina no existe'], 404);

        if ($this->isJugadorAlredyInDisciplina($validatedData['id_jugador'], $idEventoDisciplina)) {
            return response()->json(['message' => 'No es posible agregar el Jugador en la misma Disciplina'], 400);
        }

        JugadorEquipo::create([
            ...$validatedData,
            'estado' => 1
        ]);

        return response()->json(['message' => 'Jugador añadido al Equipo con éxito'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jugadorEquipo = JugadorEquipo::where('estado', 1)->find($id);
        if (is_null($jugadorEquipo))
            return response()->json(['message' => 'Jugador Equipo no encontrado'], 404);

        return response()->json($jugadorEquipo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_jugador' => 'required|integer',
            'id_equipo_disciplina' => 'required|integer',
            'id_posicion' => 'required|integer',
            'numero' => 'required|integer',
        ]);

        $idEventoDisciplina = $this->getIdEventoDisciplina($validatedData['id_equipo_disciplina']);

        if (is_null($idEventoDisciplina))
            return response()->json(['message' => 'El Equipo Disciplina no existe'], 404);

        $jugadorEquipo = JugadorEquipo::where('estado', 1)->find($id);
        if (is_null($jugadorEquipo))
            return response()->json(['message' => 'Jugador Equipo no encontrado'], 404);

        if (
            $jugadorEquipo->id_jugador != $validatedData['id_jugador'] &&
            $this->isJugadorAlredyInDisciplina($validatedData['id_jugador'], $idEventoDisciplina)
        ) {
            return response()->json(['message' => 'No es posible agregar el Jugador en la misma Disciplina'], 400);
        }

        $jugadorEquipo->fill($validatedData);
        $jugadorEquipo->save();

        return response()->json(['message' => 'Jugador Equipo actualizado con éxito'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jugadorEquipo = JugadorEquipo::where('estado', 1)->find($id);
        if (is_null($jugadorEquipo))
            return response()->json(['message' => 'Jugador Equipo no encontrado'], 404);

        $jugadorEquipo->estado = 0;
        $jugadorEquipo->save();

        return response()->json(['message' => 'Jugador Equipo eliminado con éxito'], 200);
    }

    public function deleteSelectedJugadorEquipos(Request $request)
    {
        $validatedData = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        JugadorEquipo::whereIn('id', $validatedData['ids'])->update(['estado' => 0]);

        return response()->json(['message' => 'Jugadores eliminados del Equipo con éxito'], 200);
    }

    public function getJugadorEquiposByEquipoDisciplina(string $id_equipo_disciplina)
    {
        $query = DB::table('jugador_equipos as jeq')
            ->join('jugadors as j', 'jeq.id_jugador', 'j.id')
            ->join('posicions as p', 'jeq.id_posicion', 'p.id')
            ->where('jeq.id_equipo_disciplina', $id_equipo_disciplina)
            ->where('jeq.estado', 1)
            ->where('j.estado', 1)
            ->where('p.estado', 1);

        $jugadorEquiposData = $query->get('jeq.*')->toArray();
        $jugadores = $query->get('j.*')->toArray();
        $posiciones = $query->get('p.*')->toArray();


        $jugadorEquipos = array_map(fn ($je, $j, $p) => [
            'id' => $je->id,
            'jugador' => $j,
            'id_equipo_disciplina' => (int)$id_equipo_disciplina,
            'posicion' => $p,
            'numero' => $je->numero
        ], $jugadorEquiposData, $jugadores, $posiciones);


        return response()->json($jugadorEquipos, 200);
    }

    public function getEventosByOrganizador($id_organizador)
    {
        $id_tipo_usuario = DB::table('usuarios')
            ->where('id', $id_organizador)
            ->where('estado', 1)
            ->value('id_tipo_usuario');

        if (is_null($id_tipo_usuario)) {
            return response()->json(['message' => 'No Existe Usuario'], 404);
        }

        if ($id_tipo_usuario == 1) {  // Admin
            $eventos = DB::table('eventos')
                ->where('estado', 1)
                ->get();
        } else {
            $eventos = DB::table('eventos')
                ->where('id_organizador', $id_organizador)
                ->where('estado', 1)
                ->get();
        }

        // Otra manera de hacerlo
        // $query = DB::table('eventos')->where('estado', 1);
        // if ($id_tipo_usuario != 1) $query->where('id_organizador', $id_organizador);
        // $eventos = $query->get();

        return response()->json($eventos, 200);
    }

    public function getEventoDisciplinasByEvento($id_evento)
    {
        $evento = DB::table('eventos')->where('estado', 1)->find($id_evento);

        if (is_null($evento)) {
            return response()->json(['message' => 'No existe el evento'], 404);
        }

        $eventoDisciplinasSmall = DB::table('evento_disciplinas', 'edc')
            ->join('eventos as e', 'edc.id_evento', 'e.id')
            ->join('disciplinas as d', 'edc.id_disciplina', 'd.id')
            ->join('configuracions as c', 'edc.id_configuracion', 'c.id')
            ->select(
                'edc.id',
                'd.id as id_disciplina',
                'd.nombre as nombre_disciplina',
                'e.id as id_evento',
                'e.nombre as nombre_evento',
                'c.id as id_configuracion',
                'c.nombre as nombre_configuracion',
            )
            ->where('edc.id_evento', $id_evento)
            ->where('edc.estado', 1)
            ->where('e.estado', 1)
            ->where('d.estado', 1)
            ->where('c.estado', 1)
            ->get();

        return response()->json($eventoDisciplinasSmall, 200);
    }

    public function getEventoDisciplinasFullByEvento(string $id_evento)
    {
        $evento = DB::table('eventos')->where('estado', 1)->find($id_evento);

        if (is_null($evento)) {
            return response()->json(['message' => 'No existe el evento'], 404);
        }

        $query = DB::table('evento_disciplinas as edc')
            ->join('disciplinas as d', 'edc.id_disciplina', 'd.id')
            ->join('configuracions as c', 'edc.id_configuracion', 'c.id')
            ->where('edc.id_evento', $id_evento)
            ->where('edc.estado', 1)
            ->where('d.estado', 1)
            ->where('c.estado', 1);

        $eventoDisciplinasIds = $query->pluck('edc.id')->toArray();
        $disciplinas = $query->get('d.*')->toArray();
        $configuraciones = $query->join('usuarios as u', 'c.id_organizador', 'u.id')
            ->get([
                'c.*',
                'u.nombre as nombre_organizador',
                'u.apellido as apellido_organizador'
            ])->toArray();

        $eventoDisciplinas = array_map(fn ($edId, $d, $c) => [
            'id' => $edId,
            'evento' => $evento,
            'disciplina' => $d,
            'configuracion' => $c
        ], $eventoDisciplinasIds, $disciplinas, $configuraciones);

        return response()->json($eventoDisciplinas, 200);
    }

    public function getJugadoresToAddByDisciplina($id_evento_disciplina)
    {
        $eventoDisciplina = DB::table('evento_disciplinas')
            ->where('estado', 1)
            ->find($id_evento_disciplina);

        if (is_null($eventoDisciplina)) {
            return response()->json(['message' => 'Evento Disciplina no existe'], 404);
        }

        $jugadorIdsEnDisciplina = DB::table('jugadors as j')
            ->join('jugador_equipos as jeq', 'j.id', 'jeq.id_jugador')
            ->join('equipo_disciplinas as eqd', 'eqd.id', 'jeq.id_equipo_disciplina')
            ->where('eqd.id_evento_disciplina', $id_evento_disciplina)
            ->where('j.estado', 1)
            ->where('jeq.estado', 1)
            ->where('eqd.estado', 1)
            ->pluck('j.id')->toArray();

        $jugadores = DB::table('jugadors')
            ->where('estado', 1)
            ->whereNotIn('id', $jugadorIdsEnDisciplina)
            ->get();

        return response()->json($jugadores, 200);
    }

    private function isJugadorAlredyInDisciplina($idJugador, $idEventoDisciplina)
    {
        return DB::table('jugador_equipos as jeq')
            ->join('jugadors as j', 'j.id', 'jeq.id_jugador')
            ->join('equipo_disciplinas as eqd', 'eqd.id', 'jeq.id_equipo_disciplina')
            ->where('j.id', $idJugador)
            ->where('eqd.id_evento_disciplina', $idEventoDisciplina)
            ->where('jeq.estado', 1)
            ->where('j.estado', 1)
            ->where('eqd.estado', 1)
            ->exists();
    }

    private function getIdEventoDisciplina($idEquipoDisciplina)
    {
        return DB::table('equipo_disciplinas as eqd')
            ->where('id', $idEquipoDisciplina)
            ->where('estado', 1)
            ->value('id_evento_disciplina');
    }
}
