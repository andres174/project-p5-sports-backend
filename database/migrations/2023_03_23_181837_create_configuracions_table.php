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
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_grupos');
            $table->integer('numero_miembros');
            $table->integer('minutos_juego');
            $table->integer('minutos_entre_partidos');
            $table->boolean('tarjetas');
            $table->boolean('ida_y_vuelta');
            $table->boolean('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};
