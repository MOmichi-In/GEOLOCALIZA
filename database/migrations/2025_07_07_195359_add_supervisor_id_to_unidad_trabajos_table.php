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
        Schema::table('unidad_trabajos', function (Blueprint $table) {
            // Añade la columna para el supervisor después del nombre.
            // Es `nullable` porque una unidad puede no tener supervisor asignado.
            // Si el supervisor se borra, el campo se pone a NULL.
            $table->foreignId('supervisor_id')->nullable()->after('nombre')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('unidad_trabajos', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn('supervisor_id');
        });
    }
};
