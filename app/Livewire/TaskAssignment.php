<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\TareaAsignada;
use App\Models\RegistroAsistencia; // Importado para la validación en delete()
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class TaskAssignment extends Component
{
    use WithPagination;

    // Propiedades para el formulario del modal
    public $fecha_trabajo;
    public $ciclo;
    public $correria;
    public $operador_id;
    public $selectedOperadorUnidadTrabajo = '';
    public $selectedOperadorSupervisor = ''; // Para mostrar el supervisor en el modal

    // Propiedades para el manejo del componente
    public $operadores = [];
    public $tareaId;
    public $isModalOpen = false;
    public $isEditMode = false;

    /**
     * Reglas de validación para el formulario.
     * Incluye una validación personalizada para asegurar que el operador tiene un supervisor.
     */
    protected function rules()
    {
        return [
            'fecha_trabajo' => 'required|date',
            'ciclo' => 'required|string|max:100',
            'correria' => 'required|string|max:100',
            'operador_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $operador = User::find($value);
                    if ($operador && !$operador->supervisor_id) {
                        $fail('El operador seleccionado no tiene un supervisor asignado. Por favor, asígnelo primero.');
                    }
                }, // <-- CORRECCIÓN: Se añadió la coma que faltaba aquí.
            ],
        ];
    }

    /**
     * Mensajes de error personalizados para una mejor experiencia de usuario.
     */
    protected $messages = [
        'fecha_trabajo.required' => 'La fecha de trabajo es obligatoria.',
        'ciclo.required' => 'El ciclo es obligatorio.',
        'correria.required' => 'La correría es obligatoria.',
        'operador_id.required' => 'Debe seleccionar un operador.',
        'operador_id.exists' => 'El operador seleccionado no es válido.',
    ];

    /**
     * El método mount se ejecuta una vez, cuando el componente se inicializa.
     * Ideal para cargar datos que no cambian, como la lista de operadores.
     */
    public function mount()
    {
        // MEJORA: Se usa Eager Loading para cargar las relaciones y evitar consultas N+1.
        $this->operadores = User::where('rol', User::ROLE_OPERADOR_LOGISTICO)
                                ->with(['unidadTrabajo', 'supervisor'])
                                ->orderBy('name')
                                ->get();
        $this->fecha_trabajo = Carbon::today()->toDateString();
    }

    /**
     * El método render se encarga de mostrar la vista.
     * Se ejecuta cada vez que una propiedad pública cambia.
     */
    public function render()
    {
        // MEJORA: Se usa Eager Loading aquí también para la tabla, haciendo la paginación eficiente.
        $tareasPaginadas = TareaAsignada::with(['operador.unidadTrabajo', 'supervisor'])
                                        ->orderBy('fecha_trabajo', 'desc')
                                        ->orderBy('id', 'desc')
                                        ->paginate(10);

        return view('livewire.task-assignment', [
            'tareasPaginadas' => $tareasPaginadas,
        ])->layout('layouts.app');
    }

    /**
     * Este "hook" se ejecuta automáticamente cuando la propiedad $operador_id es actualizada.
     * Es la clave de la reactividad para mostrar datos automáticos.
     */
    public function updatedOperadorId($value)
    {
        if ($value) {
            // MEJORA: Se usa la colección ya cargada en mount() en lugar de hacer una nueva consulta a la BD.
            $operador = $this->operadores->firstWhere('id', $value);

            if ($operador) {
                $this->selectedOperadorUnidadTrabajo = $operador->unidadTrabajo->nombre ?? 'Sin unidad asignada';
                $this->selectedOperadorSupervisor = $operador->supervisor->name ?? 'Sin supervisor asignado';
            }
        } else {
            $this->reset(['selectedOperadorUnidadTrabajo', 'selectedOperadorSupervisor']);
        }
    }

    /**
     * Prepara el componente para crear un nuevo registro.
     */
    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->fecha_trabajo = Carbon::today()->toDateString();
        $this->openModal();
    }

    /**
     * Guarda o actualiza el registro en la base de datos.
     * Aquí se encuentra la lógica principal del módulo.
     */
    public function store()
    {
        $this->validate();

        $operador = User::find($this->operador_id); // Se busca el operador para obtener su supervisor_id

        // Lógica para prevenir duplicados
        $exists = TareaAsignada::where('fecha_trabajo', $this->fecha_trabajo)
            ->where('operador_id', $this->operador_id)
            ->when($this->tareaId, fn ($query) => $query->where('id', '!=', $this->tareaId))
            ->exists();

        if ($exists) {
            $this->addError('operador_id', 'Este operador ya tiene una tarea asignada para esta fecha.');
            return;
        }

        // LÓGICA CLAVE: Se crea o actualiza la tarea, asegurando que se guarde el supervisor_id.
        TareaAsignada::updateOrCreate(['id' => $this->tareaId], [
            'fecha_trabajo' => $this->fecha_trabajo,
            'ciclo' => $this->ciclo,
            'correria' => $this->correria,
            'operador_id' => $this->operador_id,
            'supervisor_id' => $operador->supervisor_id, // ¡Se guarda el ID del supervisor del operador!
            // 'asignado_por_id' => auth()->id(), // Descomentar si añades esta columna para auditoría
        ]);

        session()->flash('message',
            $this->tareaId ? 'Tarea actualizada exitosamente.' : 'Tarea asignada exitosamente.');

        $this->closeModal();
    }

    /**
     * Prepara el componente para editar un registro existente.
     */
    public function edit($id)
    {
        $tarea = TareaAsignada::findOrFail($id);
        $this->resetInputFields();

        $this->tareaId = $id;
        $this->fecha_trabajo = Carbon::parse($tarea->fecha_trabajo)->toDateString();
        $this->ciclo = $tarea->ciclo;
        $this->correria = $tarea->correria;
        $this->operador_id = $tarea->operador_id;
        $this->updatedOperadorId($this->operador_id); // Rellena los campos automáticos

        $this->isEditMode = true;
        $this->openModal();
    }

    /**
     * Elimina un registro de la base de datos.
     */
    public function delete($id)
    {
        // MEJORA: Validación para no borrar tareas que ya tienen trabajo registrado.
        if (RegistroAsistencia::where('tarea_asignada_id', $id)->exists()) {
            session()->flash('error', 'No se puede eliminar la tarea porque ya tiene registros de asistencia asociados.');
            return;
        }

        TareaAsignada::destroy($id);
        session()->flash('message', 'Tarea eliminada exitosamente.');
    }

    // Métodos de utilidad para manejar el modal y limpiar los campos.
    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetInputFields()
    {
        $this->reset(['ciclo', 'correria', 'operador_id', 'selectedOperadorUnidadTrabajo', 'selectedOperadorSupervisor', 'tareaId']);
        $this->resetErrorBag();
    }
}