<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\TareaAsignada;
use App\Models\RegistroAsistencia;
use Carbon\Carbon;
use Livewire\WithPagination;

class TaskAssignment extends Component
{
    use WithPagination;

    // Propiedades del formulario
    public $tareaId;
    public $fecha_trabajo;
    public $ciclo;
    public $correria;
    public $operador_id;

    // Propiedades que se rellenan automáticamente
    public $unidad_trabajo_nombre = '';
    public $supervisor_nombre = '';

    // Propiedades de estado
    public $operadores = [];
    public $isModalOpen = false;
    public $isEditMode = false;

    /**
     * Reglas de validación para el formulario de asignación de tareas.
     */
    protected function rules() {
        return [
            'fecha_trabajo' => 'required|date',
            'ciclo' => 'required|string|max:100',
            'correria' => 'required|string|max:100',
            'operador_id' => [
                'required', 'exists:users,id',
                // La validación ahora comprueba la cadena de relaciones completa.
                function ($attribute, $value, $fail) {
                    $operador = User::with('unidadTrabajo.supervisor')->find($value);
                    if (!$operador || !$operador->unidadTrabajo) {
                        $fail('El operador seleccionado no tiene una unidad de trabajo asignada.');
                        return; // Detiene la validación si no hay unidad.
                    }
                    if (!$operador->unidadTrabajo->supervisor) {
                        $fail('La unidad de trabajo de este operador no tiene un supervisor a cargo.');
                    }
                },
            ],
        ];
    }
    
    /**
     * El método mount se ejecuta al inicializar el componente.
     */
    public function mount() {
        // Cargamos operadores CON las relaciones en cadena para ser eficientes.
        $this->operadores = User::where('rol', User::ROLE_OPERADOR_LOGISTICO)
                                ->with('unidadTrabajo.supervisor') // Carga anidada de relaciones.
                                ->orderBy('name')->get();
        $this->fecha_trabajo = Carbon::today()->toDateString();
    }
    
    /**
     * Renderiza la vista del componente.
     */
    public function render() {
        // Se cargan las tareas paginadas con sus relaciones para la tabla.
        $tareasPaginadas = TareaAsignada::with(['operador.unidadTrabajo.supervisor'])
                                        ->orderBy('fecha_trabajo', 'desc')->paginate(10);
        return view('livewire.task-assignment', ['tareasPaginadas' => $tareasPaginadas])->layout('layouts.app');
    }
    
    /**
     * Hook que se ejecuta cuando cambia el operador seleccionado.
     */
    public function updatedOperadorId($value) {
        if (empty($value)) {
            $this->reset(['unidad_trabajo_nombre', 'supervisor_nombre']);
            return;
        }

        // Se usa la colección ya cargada en mount() para no consultar de nuevo la BD.
        $operador = $this->operadores->firstWhere('id', $value);

        if ($operador) {
            $this->unidad_trabajo_nombre = $operador->unidadTrabajo->nombre ?? 'Sin Unidad Asignada';
            // Se obtiene el supervisor a través de la unidad (fuente única de la verdad).
            $this->supervisor_nombre = $operador->unidadTrabajo->supervisor->name ?? 'Sin Supervisor en la Unidad';
        }
    }
    
    /**
     * Guarda o actualiza una tarea en la base de datos.
     */
    public function store() {
        $this->validate();

        $operador = User::find($this->operador_id);
        // Se obtiene el ID del supervisor directamente de la unidad del operador.
        $supervisorId = $operador->unidadTrabajo->supervisor_id;

        // Lógica para prevenir duplicados.
        // ... (Tu lógica de duplicados puede ir aquí si la necesitas) ...

        TareaAsignada::updateOrCreate(['id' => $this->tareaId], [
            'fecha_trabajo' => $this->fecha_trabajo,
            'ciclo' => $this->ciclo,
            'correria' => $this->correria,
            'operador_id' => $this->operador_id,
            'supervisor_id' => $supervisorId, // Se guarda el ID del supervisor correcto.
        ]);

        session()->flash('message', $this->isEditMode ? 'Tarea actualizada correctamente.' : 'Tarea asignada correctamente.');
        $this->closeModal();
    }

    // --- Métodos de utilidad (manejo del modal y edición/borrado) ---

    public function create() {
        $this->resetInputFields();
        $this->isEditMode = false; // Asegurarse de que no esté en modo edición.
        $this->openModal();
    }
    
    public function edit($id) {
        $tarea = TareaAsignada::findOrFail($id);
        $this->resetInputFields();
        $this->isEditMode = true;
        
        $this->tareaId = $id;
        $this->fecha_trabajo = Carbon::parse($tarea->fecha_trabajo)->toDateString();
        $this->ciclo = $tarea->ciclo;
        $this->correria = $tarea->correria;
        $this->operador_id = $tarea->operador_id;
        
        // Rellena los campos automáticos con los datos de la tarea a editar.
        $this->updatedOperadorId($this->operador_id);
        
        $this->openModal();
    }
    
    public function delete($id) {
        // Validación para no borrar tareas con registros de asistencia.
        if (RegistroAsistencia::where('tarea_asignada_id', $id)->exists()) {
            session()->flash('error', 'No se puede eliminar la tarea, ya tiene registros de asistencia asociados.');
            return;
        }
        TareaAsignada::destroy($id);
        session()->flash('message', 'Tarea eliminada correctamente.');
    }
    
    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }
    
    private function resetInputFields() {
        // Resetea todas las propiedades públicas a su estado inicial.
        $this->reset(); 
        // Vuelve a cargar los datos por defecto del componente.
        $this->mount();
    }
}