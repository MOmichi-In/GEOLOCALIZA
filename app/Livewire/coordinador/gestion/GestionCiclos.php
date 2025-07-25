<?php

namespace App\Livewire\coordinador\gestion;

use Livewire\Component;
use App\Models\Ciclo;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionCiclos extends Component
{
    use WithPagination;

    // --- PROPIEDADES ---
    public $ciclo_id;
    public $nombre;

    public $modalVisible = false;
    public $editando = false;
    public $searchTerm = '';

    // --- MÉTODOS DEL CICLO DE VIDA ---
    
    public function render()
    {
        $ciclos = Ciclo::where('nombre', 'like', '%' . $this->searchTerm . '%')
                       ->latest()
                       ->paginate(5);

        return view('livewire.coordinador.gestion.gestion-ciclos', ['ciclos' => $ciclos])
               ->layout('layouts.app');
    }
    
    // --- LÓGICA DEL FORMULARIO ---
    
    protected function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('ciclos')->ignore($this->ciclo_id)],
        ];
    }
    
    public function guardar()
    {
        $this->validate();

        Ciclo::updateOrCreate(
            ['id' => $this->ciclo_id],
            ['nombre' => $this->nombre]
        );

        session()->flash('message', $this->editando ? 'Ciclo actualizado con éxito.' : 'Ciclo creado con éxito.');
        $this->ocultarModal();
    }
    
    // --- MÉTODOS ACCIONADOS POR BOTONES ---
    
    public function editar($id)
    {
        $ciclo = Ciclo::findOrFail($id);
        
        $this->ciclo_id = $ciclo->id;
        $this->nombre = $ciclo->nombre;
        $this->editando = true;
        
        $this->mostrarModal();
    }

    public function eliminar($id)
    {
        // Validación: No permitir borrar un ciclo si tiene correrías asociadas.
        $ciclo = Ciclo::withCount('correrias')->find($id);

        if ($ciclo && $ciclo->correrias_count > 0) {
            session()->flash('error', 'No se puede eliminar el ciclo porque tiene correrías asignadas.');
            return;
        }

        Ciclo::destroy($id);
        session()->flash('message', 'Ciclo eliminado con éxito.');
    }
    
    // --- MÉTODOS DE UTILIDAD PARA EL MODAL ---
    
    public function mostrarModal()
    {
        $this->limpiarCampos();
        $this->modalVisible = true;
    }
    
    public function ocultarModal()
    {
        $this->limpiarCampos();
        $this->modalVisible = false;
    }

    private function limpiarCampos()
    {
        $this->reset(['ciclo_id', 'nombre', 'editando']);
        $this->resetErrorBag();
    }
}