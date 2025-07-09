<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Correria;
use App\Models\Ciclo;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionCorrerias extends Component
{
    use WithPagination;

    // --- PROPIEDADES ---
    public $correria_id;
    public $nombre;
    public $ciclo_id = ''; // Para el select del formulario

    // Estas propiedades son para el estado de la interfaz
    public $modalVisible = false;
    public $editando = false;
    public $searchTerm = '';
    public $ciclos = []; // Para almacenar la lista de ciclos disponibles

    // --- MÉTODOS DEL CICLO DE VIDA ---

    // El método 'mount' se ejecuta UNA vez cuando el componente se carga.
    // Es el lugar ideal para cargar datos que no cambian, como la lista de ciclos.
    public function mount()
    {
        $this->ciclos = Ciclo::orderBy('nombre')->get();
    }
    
    public function render()
    {
        // El 'with('ciclo')' carga la relación para poder mostrar el nombre del ciclo en la tabla.
        // Es más eficiente que hacer una consulta por cada fila.
        $correrias = Correria::with('ciclo')
                           ->where('nombre', 'like', '%' . $this->searchTerm . '%')
                           ->latest()
                           ->paginate(10);

        return view('livewire.gestion-correrias', ['correrias' => $correrias])
               ->layout('layouts.app');
    }
    
    // --- LÓGICA DEL FORMULARIO ---

    protected function rules()
    {
        return [
            'nombre'   => 'required|string|max:255',
            // La correría debe pertenecer a un ciclo válido que exista en la tabla 'ciclos'.
            'ciclo_id' => 'required|exists:ciclos,id',
        ];
    }
    
    public function guardar()
    {
        $this->validate();

        Correria::updateOrCreate(
            ['id' => $this->correria_id],
            [
                'nombre'   => $this->nombre,
                'ciclo_id' => $this->ciclo_id
            ]
        );

        session()->flash('message', $this->editando ? 'Correría actualizada.' : 'Correría creada.');
        $this->ocultarModal();
    }
    
    // --- MÉTODOS ACCIONADOS POR BOTONES ---
    
    public function editar($id)
    {
        $correria = Correria::findOrFail($id);
        
        $this->correria_id = $correria->id;
        $this->nombre      = $correria->nombre;
        $this->ciclo_id    = $correria->ciclo_id; // Cargamos el ciclo al que pertenece
        $this->editando    = true;
        
        $this->mostrarModal();
    }

    public function eliminar($id)
    {
        // Futura validación: No eliminar si la correría está en uso en una TareaAsignada.
        Correria::destroy($id);
        session()->flash('message', 'Correría eliminada.');
    }
    
    // --- MÉTODOS DE UTILIDAD ---
    
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
        $this->reset(['correria_id', 'nombre', 'ciclo_id', 'editando']);
        $this->resetErrorBag();
    }
}