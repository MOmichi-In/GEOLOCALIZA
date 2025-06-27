<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\TareaAsignada;
// No necesitas App\Models\UnidadTrabajo aquí a menos que lo uses para otra cosa que no sea eager loading
use Carbon\Carbon;
use Livewire\WithPagination;

class TaskAssignment extends Component
{
    use WithPagination; // Muy importante para que la paginación funcione en la vista

    // Propiedades para el formulario del modal
    public $fecha_trabajo;
    public $ciclo;
    public $correria;
    public $operador_id;
    public $selectedOperadorUnidadTrabajo = '';

    public $operadores = []; // Para el select de operadores

    // Propiedades para el manejo del modal y la edición
    public $tareaId;
    public $isModalOpen = false;
    public $isEditMode = false;

    // NO necesitas una propiedad pública para $tareasAsignadas
    // public $tareasAsignadas = []; // <--- ELIMINA O COMENTA ESTA LÍNEA

    protected function rules()
    {
        return [
            'fecha_trabajo' => 'required|date',
            'ciclo' => 'required|string|max:100',
            'correria' => 'required|string|max:100',
            'operador_id' => 'required|exists:users,id',
        ];
    }

    protected $messages = [
        'fecha_trabajo.required' => 'La fecha de trabajo es obligatoria.',
        'ciclo.required' => 'El ciclo es obligatorio.',
        'correria.required' => 'La correría es obligatoria.',
        'operador_id.required' => 'Debe seleccionar un operador.',
        'operador_id.exists' => 'El operador seleccionado no es válido.',
    ];

    public function mount()
    {
        $this->operadores = User::where('rol', User::ROLE_OPERADOR_LOGISTICO)
                                ->orderBy('name')
                                ->get();
        $this->fecha_trabajo = Carbon::today()->toDateString();
    }

    public function render()
    {
        // Obtén los datos paginados aquí y pásalos directamente a la vista
        $tareasPaginadas = TareaAsignada::with('operador.unidadTrabajo')
                                        ->orderBy('fecha_trabajo', 'desc')
                                        ->orderBy('id', 'desc')
                                        ->paginate(10); // Esta variable es local al método render

        return view('livewire.task-assignment', [
            'tareasPaginadas' => $tareasPaginadas // Pasa la variable local a la vista
        ])->layout('layouts.app');
    }

    // Se ejecuta cuando la propiedad $operador_id cambia
    public function updatedOperadorId($value)
    {
        if ($value) {
            $operador = User::with('unidadTrabajo')->find($value);
            $this->selectedOperadorUnidadTrabajo = $operador && $operador->unidadTrabajo ? $operador->unidadTrabajo->nombre : 'Sin unidad asignada';
        } else {
            $this->selectedOperadorUnidadTrabajo = '';
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->fecha_trabajo = Carbon::today()->toDateString();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        // $this->resetInputFields(); // Es mejor resetear explícitamente o al abrir el modal de creación
    }

    private function resetInputFields()
    {
        // No resetees fecha_trabajo aquí si quieres que mantenga el valor después de guardar
        // $this->fecha_trabajo = Carbon::today()->toDateString();
        $this->ciclo = '';
        $this->correria = '';
        $this->operador_id = '';
        $this->selectedOperadorUnidadTrabajo = '';
        $this->tareaId = null;
        // $this->isEditMode = false; // Se maneja en create() y edit()
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        $exists = TareaAsignada::where('fecha_trabajo', $this->fecha_trabajo)
            ->where('ciclo', $this->ciclo)
            ->where('correria', $this->correria)
            ->where('operador_id', $this->operador_id)
            ->when($this->tareaId, function ($query) {
                return $query->where('id', '!=', $this->tareaId);
            })
            ->exists();

        if ($exists) {
            session()->flash('error', 'Ya existe una tarea asignada con los mismos datos para este operador en esta fecha.');
            return;
        }

        TareaAsignada::updateOrCreate(['id' => $this->tareaId], [
            'fecha_trabajo' => $this->fecha_trabajo,
            'ciclo' => $this->ciclo,
            'correria' => $this->correria,
            'operador_id' => $this->operador_id,
        ]);

        session()->flash('message',
            $this->tareaId ? 'Tarea actualizada exitosamente.' : 'Tarea asignada exitosamente.');

        $this->closeModal();
        $this->resetInputFields(); // Resetea después de guardar/actualizar
    }

    public function edit($id)
    {
        $this->resetInputFields(); // Buena idea resetear antes de cargar nuevos datos
        $tarea = TareaAsignada::findOrFail($id);
        $this->tareaId = $id;
        $this->fecha_trabajo = Carbon::parse($tarea->fecha_trabajo)->toDateString();
        $this->ciclo = $tarea->ciclo;
        $this->correria = $tarea->correria;
        $this->operador_id = $tarea->operador_id;
        $this->updatedOperadorId($this->operador_id); // Actualiza la unidad de trabajo mostrada
        $this->isEditMode = true;
        $this->openModal();
    }

    public function delete($id)
    {
        TareaAsignada::find($id)->delete();
        session()->flash('message', 'Tarea eliminada exitosamente.');
    }
}