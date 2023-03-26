<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisciplinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disciplinas = ['Fútbol', 'Fútbol Sala', 'Indor'];

        foreach ($disciplinas as $d) {
            DB::table('disciplinas')->insert([
                'nombre' => $d,
                'estado' => 1
            ]);
        }
    }
}
