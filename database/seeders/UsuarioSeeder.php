<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'nombre' => 'Admin',
            'apellido' => 'Principal',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'foto_perfil' => 'amdin_foto.jpg',
            'estado' => 1,
            'id_tipo_usuario' => 1,
        ]);

        DB::table('usuarios')->insert([
            'nombre' => 'Organizador',
            'apellido' => 'Prueba',
            'email' => 'organizador_prueba@gmail.com',
            'password' => Hash::make('12345678'),
            'foto_perfil' => 'organizador_foto.jpg',
            'estado' => 1,
            'id_tipo_usuario' => 2,
        ]);

        DB::table('usuarios')->insert([
            'nombre' => 'Usuario',
            'apellido' => 'Prueba',
            'email' => 'usuario_prueba@gmail.com',
            'password' => Hash::make('12345678'),
            'foto_perfil' => 'usuario_foto.jpg',
            'estado' => 1,
            'id_tipo_usuario' => 3,
        ]);
    }
}
