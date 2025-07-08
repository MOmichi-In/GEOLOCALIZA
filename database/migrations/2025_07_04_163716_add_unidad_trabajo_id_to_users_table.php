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
        Schema::table('users', function (Blueprint $table) {
            // Un usuario (operador) puede estar asignado a una unidad.
            // Es `nullable` para operadores no asignados.
            // La FK apunta a la tabla `unidad_trabajos`.
            $table->foreignId('unidad_trabajo_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unidad_trabajo_id']);
            $table->dropColumn('unidad_trabajo_id');
        });
    }
};
