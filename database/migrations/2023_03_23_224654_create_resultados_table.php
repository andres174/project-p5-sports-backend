<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->integer('puntos');
            $table->integer('goles_favor');
            $table->integer('goles_contra');

            $table->foreignId('id_equipo_disciplina')->constrained('equipo_disciplinas');
            $table->foreignId('id_partido')->constrained('partidos');

            $table->boolean('estado');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};
