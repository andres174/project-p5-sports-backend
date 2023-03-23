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
        Schema::create('jugador_equipo', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_jugador')->constrained('jugadores');
            $table->foreignId('id_equipo')->constrained('equipos');

            $table->boolean('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugador_equipo');
    }
};
