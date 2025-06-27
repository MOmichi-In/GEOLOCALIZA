<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaAsignada extends Model
{
    use HasFactory;

    protected $table = 'tarea_asignadas'; // Nombre de tu tabla

    protected $fillable = [
        'fecha_trabajo',
        'ciclo',
        'correria',
        'operador_id',
        // 'unidad_trabajo_id', // Considera si quieres almacenar esto aquí también o solo derivarlo del operador
    ];

    // Para castear la fecha a un objeto Carbon automáticamente
    protected $casts = [
        'fecha_trabajo' => 'date',
    ];

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }

    // Opcional: Si quieres tener una relación directa a la unidad de trabajo desde la tarea
    // Asumiendo que guardas unidad_trabajo_id en esta tabla.
    // Si no, puedes acceder a ella a través del operador: $tarea->operador->unidadTrabajo
    /*
    public function unidadTrabajo()
    {
        return $this->belongsTo(UnidadTrabajo::class, 'unidad_trabajo_id');
    }
    */
}