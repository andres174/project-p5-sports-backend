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
        Schema::create('jugador_equipos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_jugador')->constrained('jugadors');
            $table->foreignId('id_equipo_disciplina')->constrained('equipo_disciplinas');
            $table->foreignId('id_posicion')->constrained('posicions');

            $table->boolean('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugador_equipos');
    }
};
