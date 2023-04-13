<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipo = Equipo::where('estado', 1)->get();
        return response()->json($equipo, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        //imagen
        $img = $request->file('logo');
        $validatedData['logo'] = time() . '.' . $img->getClientOriginalExtension();

        $equipo = equipo::create([
            ...$validatedData,
            'estado' => 1,
        ]);

        $request->file('logo')->storeAs("public/logo/equipo/{$equipo->id}", $validatedData['logo']);

        return response()->json(['message' => 'Equipo registrado con éxito'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipo = Equipo::find($id);
        if (is_null($equipo)) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }
        return response()->json($equipo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $equipo = Equipo::find($id);
        if (is_null($equipo)) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $equipo->fill($validatedData);
        $equipo->save();

        return response()->json(['message' => 'Equipo actualizado con éxito'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipo = Equipo::find($id);
        if (is_null($equipo)) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        $equipo->estado = 0;
        $equipo->save();

        return response()->json(['message' => 'Equipo eliminado con éxito'], 200);
    }

    public function editarLogoEquipo(Request $request, string $id)
    {
        $equipo = Equipo::find($id);
        if (is_null($equipo)) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        //imagen
        $img = $request->file('logo');
        $validatedData['logo'] = time() . '.' . $img->getClientOriginalExtension();
        $request->file('logo')->storeAs("public/logo/equipo/{$equipo->id}", $validatedData['logo']);

        $equipo->fill($validatedData);
        $equipo->save();

        return response()->json(['message' => 'Logo de Equipo actualizado con éxito'], 200);
    }

    public function deleteSelectedEquipos(Request $request)
    {
        $validatedData = $request->validate([
            'ids' => 'required|array'
        ]);

        Equipo::whereIn('id', $validatedData['ids'])->update(['estado' => 0]);

        return response()->json(['message' => 'Equipos eliminados con éxito'], 200);
    }
}
