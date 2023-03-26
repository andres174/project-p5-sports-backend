<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posiciones = ['Arquero', 'Defensa', 'Mediocampo', 'Delantero', 'Director Técnico'];

        foreach ($posiciones as $p) {
            DB::table('posicions')->insert([
                'descripcion' => $p,
                'estado' => 1
            ]);
        }
    }
}
