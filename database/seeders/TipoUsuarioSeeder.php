<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoUsuario = ['Administrador', 'Organizador', 'Usuario'];

        foreach ($tipoUsuario as $tp) {
            DB::table('tipo_usuarios')->insert([
                'tipo' => $tp,
                'estado' => 1
            ]);
        }

    }
}
