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
        Schema::create('registro_asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_asignada_id')->constrained('tareas_asignadas');
            $table->enum('tipo', ['inicio', 'final']);
            $table->timestamp('hora_captura')->useCurrent();
            $table->decimal('latitud', 10, 7); 
            $table->decimal('longitud', 10, 7);
            $table->string('ciudad')->nullable();
            $table->string('foto_operador_path')->nullable();
            $table->text('firma_operador_path')->nullable();
            $table->text('firma_supervisor')->nullable();
            $table->string('tipo_actividad')->nullable();
            $table->boolean('usa_moto')->default(false);
            $table->foreignId('registrado_por_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_asistencias');
    }
};
