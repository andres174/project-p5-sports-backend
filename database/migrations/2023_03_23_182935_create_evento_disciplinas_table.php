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
        Schema::create('evento_disciplina', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_disciplina')->constrained('disciplinas');
            $table->foreignId('id_evento')->constrained('eventos');
            $table->foreignId('id_configuracion')->constrained('configuraciones');

            $table->boolean('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_disciplina');
    }
};
