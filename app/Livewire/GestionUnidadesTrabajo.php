<?php

namespace App\Livewire;

use App\Models\UnidadTrabajo;
use Livewire\Component;
use Livewire\WithPagination;

class GestionUnidadesTrabajo extends Component
{
    use WithPagination;

    public $nombre;
    public $unidad_id;
    public $isOpen = false;
    public $searchTerm = '';

    // Define las reglas de validación
    // La regla 'unique' se ajustará dinámicamente en 'store' para la edición
    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255|unique:unidad_trabajos,nombre' . ($this->unidad_id ? ',' . $this->unidad_id : ''),
        ];
    }

    // Opcional: mensajes de validación personalizados
    protected $messages = [
        'nombre.required' => 'El nombre de la unidad es obligatorio.',
        'nombre.unique' => 'Este nombre de unidad ya existe.',
    ];

    public function render()
    {
        $unidades = UnidadTrabajo::where('nombre', 'like', '%' . $this->searchTerm . '%')
            ->orderBy('nombre', 'asc') // Cambiado a ordenar por nombre para mejor UX
            ->paginate(10);

        return view('livewire.gestion-unidades-trabajo', [
            'unidades' => $unidades,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields(); // También resetea al cerrar para limpiar el estado
    }

    private function resetInputFields()
    {
        $this->nombre = '';
        $this->unidad_id = null;
        $this->resetErrorBag(); // Limpia errores de validación anteriores
        $this->resetValidation(); // Limpia el estado de validación
    }

    public function store()
    {
        $this->validate(); // Usará las reglas definidas en el método rules()

        UnidadTrabajo::updateOrCreate(['id' => $this->unidad_id], [
            'nombre' => $this->nombre,
        ]);

        session()->flash('message',
            $this->unidad_id ? 'Unidad de Trabajo actualizada correctamente.' : 'Unidad de Trabajo creada correctamente.');

        $this->closeModal();
        // $this->resetInputFields(); // Ya se llama en closeModal()
    }

    public function edit($id)
    {
        $unidad = UnidadTrabajo::findOrFail($id);
        $this->unidad_id = $id;
        $this->nombre = $unidad->nombre;
        $this->resetErrorBag(); // Limpia errores por si había algo antes
        $this->openModal();
    }

    public function delete($id)
    {
        $unidad = UnidadTrabajo::withCount('users')->find($id); // Cargar conteo de usuarios

        if (!$unidad) {
            session()->flash('error', 'Unidad de Trabajo no encontrada.');
            return;
        }

        if ($unidad->users_count > 0) { // 'users_count' es el alias por defecto de withCount
            session()->flash('error', 'No se puede eliminar la unidad. Hay ' . $unidad->users_count . ' usuarios asignados a ella.');
            return;
        }

        $unidad->delete();
        session()->flash('message', 'Unidad de Trabajo eliminada correctamente.');
    }

    // Para que la búsqueda se actualice mientras escribes
    public function updatedSearchTerm()
    {
        $this->resetPage(); // Resetea la paginación cuando buscas
    }
}