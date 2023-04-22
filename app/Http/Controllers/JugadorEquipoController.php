<?php

namespace App\Http\Controllers;

use App\Models\JugadorEquipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class JugadorEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function show(JugadorEquipo $jugadorEquipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JugadorEquipo $jugadorEquipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JugadorEquipo $jugadorEquipo)
    {
        //
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

    public function getEventoDisciplinasSmallByEvento($id_evento)
    {
        $evento = DB::table('eventos')
            ->where('id', $id_evento)
            ->where('estado', 1)
            ->first();

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

    public function getConfiguracion($id)
    {
        $configuracion = DB::table('configuracions', 'c')
            ->join('usuarios as u', 'c.id_organizador', 'u.id')
            ->select('c.*', 'u.nombre as nombre_organizador', 'u.apellido as apellido_organizador')
            ->where('c.id', $id)
            ->where('c.estado', 1)
            ->where('u.estado', 1)
            ->first();

        if (is_null($configuracion)) {
            return response()->json(['message' => 'No existe la configuración'], 404);
        }

        return response()->json($configuracion, 200);
    }

    public function getEventoDisciplinasByEvento(string $id_evento)
    {
        $eventoDisciplinasIds = DB::table('evento_disciplinas as edc')
            ->where('id_evento', $id_evento)
            ->pluck('id')->toArray();


        $disciplinas = DB::table('disciplinas as d')
            ->join('evento_disciplinas as edc', 'd.id', 'edc.id_disciplina')
            ->select('d.*')
            ->where('edc.id_evento', $id_evento)
            ->get()->toArray();

        $configuraciones = DB::table('configuracions as c')
            ->join('evento_disciplinas as edc', 'c.id', 'edc.id_configuracion')
            ->join('usuarios as u', 'c.id_organizador', 'u.id')
            ->select(
                'c.*',
                'u.nombre as nombre_organizador',
                'u.apellido as apellido_organizador'
            )
            ->where('edc.id_evento', $id_evento)
            ->get()->toArray();

        $eventoDisciplinas = array_map(function ($edId, $d, $c) {

            // if ($d->estado == '0' || $c->estado == '0')
            // return;
            // if ($edId == 3) return;

            return [
                'id' => $edId,
                'disciplina' => $d,
                'configuracion' => $c
            ];
        }, $eventoDisciplinasIds, $disciplinas, $configuraciones);

        return response()->json($eventoDisciplinas, 200);
    }
}
