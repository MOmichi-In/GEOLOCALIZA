<?php

// El namespace puede ser App\Livewire\Supervisor o solo App\Livewire,
// dependiendo de si moviste el archivo o solo lo renombraste.
namespace App\Livewire\supervisor;

use Livewire\Component;
use App\Models\TareaAsignada;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

// ¡CAMBIO CLAVE! El nombre de la clase ahora es PanelTareas.
class PanelTareas extends Component
{
    use WithPagination;

    // Filtros
    public $filtroFecha;
    public $filtroEstado = '';
    public $filtroSupervisor = '';
    public $searchTerm = '';

    // Lista para el filtro
    public $supervisores = [];

    public function mount()
    {
        if (auth()->user()->rol === User::ROLE_COORDINADOR_ADMINISTRATIVO) {
            $this->supervisores = User::where('rol', User::ROLE_SUPERVISOR)->orderBy('name')->get();
        }
    }

    public function render()
    {
        $query = TareaAsignada::with([
            'operador',         // relación con el usuario operador
            'supervisor',       // relación supervisor directo
            'actividad',        // tipo de tarea
        ]);

        // Aplicar filtros si los tienes...
        if ($this->searchTerm) {
            $query->whereHas('operador', function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->filtroFecha) {
            $query->whereDate('fecha_inicio', $this->filtroFecha);
        }

        if ($this->filtroSupervisor && auth()->user()->rol === User::ROLE_COORDINADOR_ADMINISTRATIVO) {
            $query->where('supervisor_id', $this->filtroSupervisor);
        }

        $tareas = $query->orderBy('fecha_inicio', 'desc')->paginate(10);

        return view('livewire.supervisor.panel-tareas', [
            'tareas' => $tareas,
            'supervisores' => User::where('rol', User::ROLE_SUPERVISOR)->orderBy('name')->get(),
        ])->layout('layouts.app'); // <-- esta línea es clave

    }


    public function updating($key)
    {
        if (in_array($key, ['filtroFecha', 'filtroEstado', 'filtroSupervisor', 'searchTerm'])) {
            $this->resetPage();
        }
    }
}