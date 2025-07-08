<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaAsignada extends Model
{
    use HasFactory;

    protected $table = 'tarea_asignadas';

    // ¡IMPORTANTE! Añadimos supervisor_id aquí
    protected $fillable = [
        'fecha_trabajo',
        'ciclo',
        'correria',
        'operador_id',
        'supervisor_id',
    ];

    protected $casts = [
        'fecha_trabajo' => 'date',
    ];

    // Relación con el Operador (User)
    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }

    // ¡IMPORTANTE! Añadimos la relación con el Supervisor (User)
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}