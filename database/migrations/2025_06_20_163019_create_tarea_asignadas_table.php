<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tarea_asignadas', function (Blueprint $table) {
            $table->id();
            $table->string('ciclo');
            $table->string('correria');
            $table->foreignId('operador_id')->constrained('users')->comment('FK a users donde rol es implicitamente Operador Logistico (aunque no acceden)');
            $table->foreignId('supervisor_id')->constrained('users');
            $table->integer('cantidad')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarea_asignadas');
    }
};
