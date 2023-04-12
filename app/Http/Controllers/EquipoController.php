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
        $equipo = equipo::where('estado',1)->get();
        return response()->json($equipo, 200);
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
            'nombre'=>'required|string|max:255',
        ]);
        //imagen
        $img = $request->file('logo');
        $valiData['logo'] =  time().'.'.$img->getClientOriginalExtension();


        $equipo=equipo::create([
            'nombre'=>$validateData['nombre'],
            'logo'=>$valiData['logo'],
            'estado'=>1,
        ]);

        $request->file('logo')->storeAs("public/logo/equipo/{$equipo->id}", $valiData['logo']);

        return response()->json(['message'=>'Equipo registrado Con exito'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipo $equipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipo $equipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipo $equipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        //
    }
}
