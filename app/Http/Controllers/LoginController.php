<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    //Sí, creé un controlador solo para esto

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $usuario = Usuario::where('email', $request->email)->first();

        $token = $usuario->createToken('auth_token')->plainTextToken;

        $res = DB::table('usuarios')
        ->join('tipo_usuarios', 'usuarios.id_tipo_usuario', '=', 'tipo_usuarios.id')
        ->select('usuarios.*', 'tipo_usuarios.*')
        ->where('usuarios.email', $usuario->email)
        ->get();

        return response()->json(
            [

                'accesToken' => $token,
                'tokenType' => 'Bearer',
                'typeUserId' => $usuario->id_tipo_usuario,
                'id' => $usuario->id,
                'userName' => $usuario->nombre,
                'email' => $usuario->email,
                'rol' => $res[0]->tipo,
                'message' => "Credenciales válidas"

            ], 200

        );

    }
}