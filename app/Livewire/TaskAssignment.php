<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\TareaAsignada;
use App\Models\RegistroAsistencia;
use App\Models\Ciclo;
use App\Models\Correria;
use App\Models\Actividad;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class TaskAssignment extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $tareaId;
    public $ciclo_id = '';
    public $correria_id = '';
    public $actividad_id = '';
    public $operador_id = '';
    public $cantidad;


    // --- PROPIEDADES AUTOCOMPLETADAS ---
    public $unidad_trabajo_nombre = '';
    public $supervisor_nombre = '';
    public $operador_cedula = '';

    // --- COLECCIONES PARA LOS SELECTS ---
    public $operadores = [], $ciclos = [], $correrias = [], $actividades = [];

    // --- PROPIEDADES DE ESTADO ---
    public $isModalOpen = false;
    public $isEditMode = false;

    // --- REGLAS DE VALIDACIÓN ---
    protected function rules()
    {
        return [
            'ciclo_id' => 'required|exists:ciclos,id',
            'correria_id' => 'required|exists:correrias,id',
            'actividad_id' => 'required|exists:actividades,id',
            'cantidad' => 'required|integer|min:1',
            'operador_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $operador = User::with('unidadTrabajo')->find($value);
                    if (!$operador || !$operador->unidadTrabajo) {
                        $fail('Este operador no tiene una unidad de trabajo asignada.');
                    }
                },
            ],
        ];
    }

    // --- MÉTODOS DEL CICLO DE VIDA ---
    public function mount()
    {
        $this->operadores = User::where('rol', User::ROLE_OPERADOR_LOGISTICO)
            ->with(['unidadTrabajo.supervisor'])
            ->orderBy('name')->get();
        $this->ciclos = Ciclo::orderBy('nombre')->get();
        $this->actividades = Actividad::orderBy('nombre')->get();
    }

    public function render()
    {
        $tareasPaginadas = TareaAsignada::with([
            'operador' => fn($q) => $q->select('id', 'name', 'cedula'),
            'ciclo',
            'correria',
            'actividad'
        ])
            ->latest('fecha_inicio')
            ->paginate(10);

        return view('livewire.task-assignment', ['tareasPaginadas' => $tareasPaginadas])->layout('layouts.app');
    }

    // --- HOOKS DE PROPIEDADES (LÓGICA DINÁMICA) ---
    public function updatedCicloId($cicloId)
    {
        if (!empty($cicloId)) {
            $this->correrias = Correria::where('ciclo_id', $cicloId)->orderBy('nombre')->get();
        } else {
            $this->correrias = [];
        }
        $this->reset('correria_id');
    }

    public function updatedOperadorId($operadorId)
    {
        if (empty($operadorId)) {
            $this->reset(['unidad_trabajo_nombre', 'supervisor_nombre', 'operador_cedula']);
            return;
        }

        $operador = $this->operadores->firstWhere('id', $operadorId);

        if ($operador) {
            $this->unidad_trabajo_nombre = $operador->unidadTrabajo->nombre ?? 'Sin Unidad';
            $this->supervisor_nombre = $operador->unidadTrabajo->supervisor->name ?? 'Sin Supervisor';
            $this->operador_cedula = $operador->cedula ?? 'Sin Cédula';
        }
    }

    // --- ACCIONES PRINCIPALES ---
    public function store()
    {
        $this->validate();

        // 1. Cargamos el operador Y su unidad de trabajo de forma explícita.
        $operador = User::with('unidadTrabajo')->find($this->operador_id);

        // 2. Primera barrera de seguridad: ¿Existe la unidad de trabajo?
        if (!$operador || !$operador->unidadTrabajo) {
            // Esto no debería pasar por la validación, pero es una buena práctica.
            session()->flash('error', 'Error crítico: La unidad de trabajo del operador no fue encontrada.');
            $this->closeModal();
            return;
        }

        // 3. Obtenemos el ID del supervisor DIRECTAMENTE desde la unidad.
        $supervisorId = $operador->unidadTrabajo->supervisor_id;

        // 4. Segunda barrera de seguridad: ¿Tiene la unidad un supervisor asignado?
        if (is_null($supervisorId)) {
            session()->flash('error', 'La unidad de este operador no tiene un supervisor asignado. No se puede crear la tarea.');
            $this->closeModal();
            return;
        }

        // Con la certeza de que tenemos un supervisor_id, procedemos a guardar.
        TareaAsignada::updateOrCreate(['id' => $this->tareaId], [
            'fecha_inicio' => $this->isEditMode ? TareaAsignada::find($this->tareaId)->fecha_inicio : now(),
            'ciclo_id' => $this->ciclo_id,
            'correria_id' => $this->correria_id,
            'actividad_id' => $this->actividad_id,
            'operador_id' => $this->operador_id,
            'supervisor_id' => $supervisorId, // ¡Usamos el ID verificado!
            'cantidad' => $this->cantidad, 
            'estado' => 'Asignada',
        ]);

        session()->flash('message', $this->isEditMode ? 'Tarea actualizada.' : 'Tarea asignada.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $tarea = TareaAsignada::findOrFail($id);

        // No se puede editar si ya no está en estado "Asignada"
        if ($tarea->estado !== 'Asignada') {
            session()->flash('error', 'Solo se pueden editar tareas en estado "Asignada".');
            return;
        }

        $this->isEditMode = true;
        $this->tareaId = $id;
        $this->ciclo_id = $tarea->ciclo_id;

        $this->updatedCicloId($this->ciclo_id); // Carga las correrías del ciclo

        $this->correria_id = $tarea->correria_id;
        $this->actividad_id = $tarea->actividad_id;
        $this->operador_id = $tarea->operador_id;
        $this->cantidad = $tarea->cantidad;

        $this->updatedOperadorId($this->operador_id);

        $this->openModal();
    }

    public function delete($id)
    {
        $tarea = TareaAsignada::find($id);

        if ($tarea && $tarea->estado !== 'Asignada') {
            session()->flash('error', 'Solo se pueden eliminar tareas en estado "Asignada".');
            return;
        }

        $tarea->delete();
        session()->flash('message', 'Tarea eliminada.');
    }

    // --- MÉTODOS DE UTILIDAD ---
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }
    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->reset();
        $this->mount();
        $this->resetErrorBag();
    }
}