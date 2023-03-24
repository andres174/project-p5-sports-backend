<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
class JugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jugador = DB::table('jugadors')
        ->join('posiciones','jugadors.id_posicion','=','posiciones.id')
        ->select('jugadors.*','posiciones.descripcion as posicione')
        ->where('posiciones.estado',1)
        ->get();
        return response()->json($jugador, 200);
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
            'apellido'=>'required|string|max:255',
            'cedula'=>'required|string|max:255',
            'numero'=>'required|string|max:255',
            'foto' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'id_posicion'=>'required',
        ]);
        //imagen
        $img = $request->file('foto');
        $valiData['foto'] =  time().'.'.$img->getClientOriginalExtension();


        $jugador=jugador::create([
            'nombre'=>$validateData['nombre'],
            'apellido'=>$validateData['apellido'],
            'cedula'=>$validateData['cedula'],
            'numero'=>$validateData['numero'],
            'foto'=>$valiData['foto'],
            'id_posicion'=>$validateData['id_posicion'],
            'estado'=>1,
        ]);

        $request->file('foto')->storeAs("public/foto/jugador/{$jugador->id}", $valiData['foto']);

        return response()->json(['message'=>'Jugador registrado'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jugador=jugador::find($id);
        if (is_null($jugador)) {
            return response()->json(['message' => 'jugador no encontrado'], 404);
        }
        return response()->json($jugador);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jugador $jugador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $jugador = jugador::find($id);
        if (is_null($jugador)) {
            return response()->json(['message' => 'Jugador no encontrado.'], 404);
        }
        $validateData = $request->validate([
            'nombre'=>'required|string|max:255',
            'apellido'=>'required|string|max:255',
            'cedula'=>'required|string|max:255',
            'numero'=>'required|string|max:255',
            'id_posicion'=>'required',

        ]);
        $jugador->nombre = $validateData['nombre'];
        $jugador->apellido = $validateData['apellido'];
        $jugador->cedula = $validateData['cedula'];
        $jugador->numero = $validateData['numero'];
        $jugador->id_posicion = $validateData['id_posicion'];
        $jugador->save();
        return response()->json(['message' => 'Jugador actualizado'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jugador=jugador::find($id);
        if (is_null($jugador)) {
            return response()->json(['message' => 'jugador no encontrado'], 404);
        }
        $jugador->estado=0;
        $jugador->save();
        return response()->json(['message'=>'jugador eliminado']);
    }

    public function editarFotoJugador(Request $request, $id ){

        $jugador = jugador::find($id);
        if (is_null($jugador)) {
            return response()->json(['message' => 'Foto no encontrada.'], 404);
        }
        $validateData = $request->validate([
            'foto' => 'required|mimes:jpeg,bmp,png',
        ]);
        $img=$request->file('foto');
        $validateData['foto'] = time().'.'.$img->getClientOriginalExtension();
        $request->file('foto')->storeAs("public/foto/jugador/{$jugador->id}", $validateData['foto']);
        $jugador->foto=$validateData['foto'];
        $jugador->save();
        return response()->json(['message' => 'Foto del jugador actualizada'], 201);
    }
}
