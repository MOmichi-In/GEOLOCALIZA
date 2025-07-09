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
        Schema::create('correrias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');

            // ¡LA RELACIÓN CLAVE!
            // Esta columna 'ciclo_id' enlazará con la tabla 'ciclos'.
            $table->foreignId('ciclo_id')
                  ->constrained('ciclos') // Asegura que el ID exista en la tabla 'ciclos'.
                  ->onDelete('cascade');  // Si un ciclo se elimina, todas sus correrías se eliminan también.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correrias');
    }
};