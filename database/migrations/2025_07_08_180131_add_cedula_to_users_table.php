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
        Schema::table('users', function (Blueprint $table) {
            // Añadimos la columna 'cedula' después de la columna 'rol'.
            // 'nullable()' por si tienes usuarios existentes o no quieres que sea obligatoria para todos los roles.
            // 'unique()' para asegurar que no haya dos usuarios con la misma cédula.
            $table->string('cedula')->nullable()->unique()->after('rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Esto permite revertir la migración de forma segura.
            $table->dropColumn('cedula');
        });
    }
};