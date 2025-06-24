<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroAsistencia extends Model
{
    //
    protected $fillable = [
        'tarea_asignada_id',
        'tipo',
        'hora_captura',
        'latitud',
        'longitud',
        'ciudad',
        'foto_operador_path',
        'firma_operador_path',
        'firma_supervisor',
        'tipo_actividad',
        'usa_moto',
        'registrado_por_id',
    ];

    public function tareaAsignada() {
        return $this->belongsTo(TareaAsignada::class);
    }

    public function supervisorRegistra() {
        return $this->belongsTo(User::class, 'registrado_por_id');
    }
}
