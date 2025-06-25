<?php

namespace App\Livewire;

use App\Models\UnidadTrabajo;
use Livewire\Component;
use Livewire\WithPagination; // Para paginación

class GestionUnidadesTrabajo extends Component
{
    use WithPagination;

    public $nombre;
    public $unidad_id;
    public $isOpen = false;
    public $searchTerm = '';

    protected $rules = [
        'nombre' => 'required|string|max:255|unique:unidad_trabajos,nombre',
    ];

    public function render()
    {
        $unidades = UnidadTrabajo::where('nombre', 'like', '%'.$this->searchTerm.'%')
                                ->orderBy('id', 'desc')
                                ->paginate(10);
        return view('livewire.gestion-unidades-trabajo', [
            'unidades' => $unidades,
        ])->layout('layouts.app'); // Asumiendo que usas el layout de Breeze
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
    }

    private function resetInputFields(){
        $this->nombre = '';
        $this->unidad_id = null; // Cambiado para claridad
    }

    public function store()
    {
        $this->validate(
            $this->unidad_id ? ['nombre' => 'required|string|max:255|unique:unidad_trabajos,nombre,'.$this->unidad_id] : $this->rules
        );

        UnidadTrabajo::updateOrCreate(['id' => $this->unidad_id], [
            'nombre' => $this->nombre,
        ]);

        session()->flash('message',
            $this->unidad_id ? 'Unidad de Trabajo actualizada correctamente.' : 'Unidad de Trabajo creada correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $unidad = UnidadTrabajo::findOrFail($id);
        $this->unidad_id = $id;
        $this->nombre = $unidad->nombre;
        $this->openModal();
    }

    public function delete($id)
    {
        // Opcional: verificar si hay usuarios asignados antes de borrar
        $unidad = UnidadTrabajo::find($id);
        if ($unidad && $unidad->users()->count() > 0) {
             session()->flash('error', 'No se puede eliminar la unidad. Hay usuarios asignados a ella.');
             return;
        }
        $unidad->delete();
        session()->flash('message', 'Unidad de Trabajo eliminada correctamente.');
    }
}