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
        Schema::create('grupo_equipo', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_equipo')->constrained('equipos');
            $table->foreignId('id_grupo')->constrained('grupos');

            $table->boolean('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_equipo');
    }
};
