<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaAsignada extends Model
{
    use HasFactory;

    protected $table = 'tarea_asignadas';

    /**
     * La lista completa de atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'operador_id',
        'supervisor_id',
        'ciclo_id',
        'correria_id',
        'actividad_id',
        'fecha_inicio',
        'fecha_entrega',
        'observaciones',
        'firma_inicio',
        'firma_final',
        'firma_supervisor',
        'estado',
        'cantidad',
    ];

    /**
     * Define conversiones automáticas de tipos de datos.
     */
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_entrega' => 'datetime',
    ];

    // ================================================================
    //             RELACIONES ELOQUENT (AQUÍ ESTÁ LA SOLUCIÓN)
    // ================================================================

    /**
     * RELACIÓN: Una tarea pertenece a UN Operador (User).
     */
    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }

    /**
     * RELACIÓN: Una tarea es responsabilidad de UN Supervisor (User).
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    
    /**
     * RELACIÓN: Una tarea pertenece a UN Ciclo.
     */
    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'ciclo_id');
    }
    
    /**
     * RELACIÓN: Una tarea pertenece a UNA Correría.
     */
    public function correria()
    {
        return $this->belongsTo(Correria::class, 'correria_id');
    }

    /**
     * RELACIÓN: Una tarea tiene UNA Actividad asociada.
     */
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
}