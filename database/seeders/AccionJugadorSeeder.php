<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccionJugadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accionJugador = ['Gol', 'Tarjeta Amarilla', 'Tarjeta Roja', 'Tiro libre', 'Penal', 'Tiro de esquina'];

        foreach ($accionJugador as $aj) {
            DB::table('accion_jugadors')->insert([
                'descripcion' => $aj,
                'estado' => 1
            ]);
        }
    }
}
