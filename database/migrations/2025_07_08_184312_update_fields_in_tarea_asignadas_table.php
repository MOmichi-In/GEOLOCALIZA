<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tarea_asignadas', function (Blueprint $table) {
            // 1. ELIMINAR las columnas antiguas de texto plano.
            // Es importante comprobar si existen antes de borrarlas.
            if (Schema::hasColumn('tarea_asignadas', 'ciclo')) {
                $table->dropColumn('ciclo');
            }
            if (Schema::hasColumn('tarea_asignadas', 'correria')) {
                $table->dropColumn('correria');
            }

            // 2. AÑADIR las nuevas columnas relacionadas (después de 'supervisor_id')
            $table->foreignId('ciclo_id')->nullable()->after('supervisor_id')->constrained('ciclos');
            $table->foreignId('correria_id')->nullable()->after('ciclo_id')->constrained('correrias');
            $table->foreignId('actividad_id')->nullable()->after('correria_id')->constrained('actividades');
            
            // 3. AÑADIR los nuevos campos de ciclo de vida de la tarea
            $table->dateTime('fecha_inicio')->nullable()->comment('Se establece al crear la tarea');
            $table->dateTime('fecha_entrega')->nullable()->comment('Establecida por el supervisor');
            $table->text('observaciones')->nullable();
            $table->longText('firma_inicio')->nullable(); // 'longText' para guardar firmas en Base64
            $table->longText('firma_final')->nullable();
            $table->longText('firma_supervisor')->nullable();
            $table->string('estado')->default('Asignada'); // Estados: Asignada, EnProgreso, Finalizada, Cancelada
        });
    }

    public function down(): void
    {
        // El método 'down' debería revertir todos los cambios de 'up'
        Schema::table('tarea_asignadas', function (Blueprint $table) {
            // Añade de nuevo las columnas de texto (si quieres poder revertir completamente)
            $table->string('ciclo');
            $table->string('correria');
            
            // Elimina las columnas añadidas
            $table->dropForeign(['ciclo_id']);
            $table->dropForeign(['correria_id']);
            $table->dropForeign(['actividad_id']);
            $table->dropColumn(['ciclo_id', 'correria_id', 'actividad_id', 'fecha_inicio', 'fecha_entrega', 'observaciones', 'firma_inicio', 'firma_final', 'firma_supervisor', 'estado']);
        });
    }
};