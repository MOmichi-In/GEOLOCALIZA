<?php

namespace App\Livewire;

use App\Models\UnidadTrabajo;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GestionUnidadesTrabajo extends Component
{
    use WithPagination;

    // Propiedades del formulario
    public $unidad_id;
    public $nombre;
    public $supervisor_id_actual; // El supervisor asignado a la unidad

    // Propiedades para la asignación de operadores
    public $operadoresAsignados = [];
    public $todosLosOperadores = []; // Colección completa de todos los operadores

    // Propiedades de estado y búsqueda
    public $isOpen = false;
    public $searchTerm = '';
    
    // Lista de supervisores para el dropdown
    public $supervisores = [];

    protected function rules() {
        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('unidad_trabajos')->ignore($this->unidad_id)],
            'supervisor_id_actual' => 'nullable|exists:users,id',
            'operadoresAsignados' => 'sometimes|array', // `sometimes` para que no falle al crear (cuando no se muestran los checkboxes)
        ];
    }

    public function mount() {
        // Carga una vez las listas que no cambian para mayor eficiencia
        $this->supervisores = User::where('rol', User::ROLE_SUPERVISOR)->orderBy('name')->get();
        // Carga operadores con la relación de la unidad a la que ya pertenecen
        $this->todosLosOperadores = User::where('rol', User::ROLE_OPERADOR_LOGISTICO)
                                        ->with('unidadTrabajo')
                                        ->orderBy('name')->get();
    }

    public function render() {
        $unidades = UnidadTrabajo::with('supervisor')->withCount('operadores')
            ->where('nombre', 'like', '%' . $this->searchTerm . '%')
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        return view('livewire.gestion-unidades-trabajo', [
            'unidades' => $unidades,
        ])->layout('layouts.app');
    }

    public function crear() {
        $this->resetInputFields();
        $this->openModal();
    }
    
    public function editar($id) {
        $unidad = UnidadTrabajo::findOrFail($id);
        
        $this->unidad_id = $id;
        $this->nombre = $unidad->nombre;
        $this->supervisor_id_actual = $unidad->supervisor_id;
        
        // CORRECCIÓN CLAVE: Así se cargan correctamente los operadores para los checkboxes
        $this->operadoresAsignados = User::where('unidad_trabajo_id', $id)->pluck('id')->toArray();
        
        $this->openModal();
    }

    public function store() {
        $this->validate();

        DB::transaction(function () {
            // 1. Crear o actualizar la Unidad de Trabajo con su supervisor
            $unidad = UnidadTrabajo::updateOrCreate(['id' => $this->unidad_id], [
                'nombre' => $this->nombre,
                'supervisor_id' => empty($this->supervisor_id_actual) ? null : $this->supervisor_id_actual,
            ]);

            // 2. Desasignar a TODOS los operadores que previamente estaban en ESTA unidad
            User::where('unidad_trabajo_id', $unidad->id)->update(['unidad_trabajo_id' => null]);
            
            // 3. Asignar la unidad a los operadores recién seleccionados con el checkbox
            if (!empty($this->operadoresAsignados)) {
                // `whereIn` busca a todos los usuarios cuyos IDs estén en el array.
                User::whereIn('id', $this->operadoresAsignados)->update(['unidad_trabajo_id' => $unidad->id]);
            }
        });

        session()->flash('message', 'Unidad de Trabajo guardada exitosamente.');
        $this->closeModal();
    }

    public function deleteUnit() {
        // La validación en el modal `wire:confirm` es la primera barrera.
        // Aquí podríamos añadir lógica extra si fuera necesario.
        UnidadTrabajo::destroy($this->unidad_id);
        session()->flash('message', 'Unidad de Trabajo eliminada.');
        $this->closeModal();
    }
    
    // Funciones de utilidad
    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; }
    private function resetInputFields() {
        $this->reset(); // Resetea TODAS las propiedades públicas
        $this->mount(); // Vuelve a cargar los datos iniciales
    }
}