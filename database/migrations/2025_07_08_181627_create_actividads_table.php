<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Esto se ejecuta cuando corres 'php artisan migrate'.
     */
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id(); // Crea una columna 'id' autoincremental
            $table->string('nombre')->unique(); // Crea una columna de texto para el nombre, y asegura que no haya dos actividades con el mismo nombre.
            $table->timestamps(); // Crea las columnas 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     * Esto se ejecuta si necesitas deshacer la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};