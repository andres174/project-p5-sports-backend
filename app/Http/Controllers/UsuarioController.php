<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Usuario::where('estado', 1)->get();
        return response()->json($user, 200);
    }

    public function getOrganizadores()
    {
        $organizadores = Usuario::where("id_tipo_usuario", 2)
            ->where('estado', 1)
            ->get();
        return response()->json($organizadores, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'apellido' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8|max:255',
            'foto_perfil' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'id_tipo_usuario' => 'nullable|integer'
        ]);

        // Organizador por defecto
        if (empty($validatedData['id_tipo_usuario'])) {
            $validatedData['id_tipo_usuario'] = 2;
        }

        if (isset($validatedData['foto_perfil'])) {
            $img = $request->file('foto_perfil');
            $validatedData['foto_perfil'] = time() . '.' . $img->getClientOriginalExtension();
        } else {
            $validatedData['foto_perfil'] = null;
        }

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = Usuario::create([
            ...$validatedData,
            'estado' => 1
        ]);

        if (isset($user->foto_perfil)) {
            $img->storeAs("public/foto/usuario/{$user->id}", $validatedData['foto_perfil']);
        }

        return response()->json(['message' => 'Usuario registrado'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Usuario::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Usuario::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'nombre' => 'required|string|min:2|max:255',
            'apellido' => 'required|string|min:2|max:255',
            // 'email' => 'nullable|string|email|max:255',
            // 'password' => 'nullable|string|min:8|max:255',
            // 'id_tipo_usuario' => 'nullable|integer'
        ]);

        $user->fill($validatedData);
        $user->save();

        return response()->json(['message' => 'Usuario actualizado'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Usuario::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->estado = 0;
        $user->save();

        return response()->json(['message' => 'Usuario eliminado'], 200);
    }

    public function editarEmailUsuario(Request $request, string $id)
    {
        $user = Usuario::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255|unique:usuarios',
        ]);

        $user->fill($validatedData);
        $user->save();

        return response()->json(['message' => 'Correo electronico actualizado'], 200);
    }

    public function editarPasswordUsuario(Request $request, string $id)
    {
        $user = Usuario::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user->fill($validatedData);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizado'], 200);
    }

    public function editarFotoUsuario(Request $request, string $id)
    {
        $user = Usuario::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'foto_perfil' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $img = $request->file('foto_perfil');
        $validatedData['foto_perfil'] = time() . '.' . $img->getClientOriginalExtension();
        $img->storeAs("public/foto/usuario/{$user->id}", $validatedData['foto_perfil']);

        $user->fill($validatedData);
        $user->save();

        return response()->json(['message' => 'Foto de perfil actualizada'], 200);
    }
}
