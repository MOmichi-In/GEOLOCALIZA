<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RegistroAsistencia; // ¡Importante! Usar tu modelo correcto
use App\Models\TareaAsignada; // Si vas a seleccionar una tarea asignada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class RegistrosAsistencia extends Component
{
    // Propiedades del formulario que se sincronizarán con la vista
    public $tarea_asignada_id; // Nuevo campo
    public $tipo; // Nuevo campo: 'inicio' o 'final'
    public $hora_captura; // Cambiado de 'captured_at'// Cambiado de 'supervisor_signature_base64'
    public $tipo_actividad; // Cambiado de 'activity_type'
    public $usa_moto = false; // Cambiado de 'uses_motorcycle'
    public $message = '';
    public $isLoading = false;


    public $activityTypes = [
        'Selecciona',
        'Tipo1',
        'Tipo2',
    ];

    public $registroTipos = [
        'Selecciona',
        'inicio',
        'final'
    ];

    // Lista de tareas asignadas (para el selector)
    public $tareasAsignadas = [];

    // Reglas de validación para el formulario
    protected $rules = [
        'tarea_asignada_id' => 'required|exists:tareas_asignadas,id', // Validar que la tarea exista
        'tipo' => 'required|in:inicio,final',
        'hora_captura' => 'required|date',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
        'ciudad' => 'nullable|string|max:255',
        'foto_operador_base64' => 'nullable|string',
        'firma_operador_base64' => 'nullable|string',
        'firma_supervisor_base64' => 'nullable|string',
        'tipo_actividad' => 'required|string|max:255',
        'usa_moto' => 'boolean',
    ];

    public function mount()
    {
        $this->hora_captura = Carbon::now()->format('Y-m-d H:i:s');
        $this->tipo_actividad = $this->activityTypes[0]; // Valor por defecto
        $this->tipo = $this->registroTipos[0]; // Valor por defecto 'inicio'
        $this->tareasAsignadas = TareaAsignada::all(); // O TareaAsignada::where('supervisor_id', auth()->id())->get();
    }

    public function render()
    {
        return view('livewire.registros-asistencia')->layout('layouts.app');
    }
}