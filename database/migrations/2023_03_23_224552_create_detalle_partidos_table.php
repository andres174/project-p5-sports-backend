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
        Schema::create('detalle_partidos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_partido')->constrained('partidos');
            $table->foreignId('id_jugador')->constrained('jugadores');
            $table->foreignId('id_accion_jugador')->constrained('accion_jugador');

            $table->boolean('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_partidos');
    }
};
