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
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->integer('equipo_1'); //esto podría o debería ser una conexión a la otra tabla XDD 
            $table->integer('equipo_2'); //esto podría o debería ser una conexión a la otra tabla XDD 
            $table->dateTime('fecha_hora');
            $table->boolean('isPlay');
            $table->string('lugar');
            $table->float('lat'); //LATITUD
            $table->float('lng'); //LONGITUD

            $table->foreignId('id_grupo')->constrained('grupos');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
