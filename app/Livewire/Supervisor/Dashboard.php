<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use App\Models\TareaAsignada;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $filtroFecha;

    public function mount()
    {
        // Por defecto, mostrar las tareas de hoy.
        $this->filtroFecha = now()->format('Y-m-d');
    }

    public function render()
    {
        // ¡LA MAGIA OCURRE AQUÍ!
        $tareas = TareaAsignada::query()
            // Carga previa de relaciones para eficiencia
            ->with(['operador', 'supervisor'])
            // El filtro MÁS IMPORTANTE: solo tareas cuyo supervisor_id es el del usuario logueado
            ->where('supervisor_id', auth()->id())
            // Filtrar por la fecha seleccionada
            ->when($this->filtroFecha, function ($query) {
                $query->whereDate('fecha_trabajo', $this->filtroFecha);
            })
            ->orderBy('fecha_trabajo', 'desc')
            ->paginate(10);

        return view('livewire.supervisor.dashboard', [
            'tareas' => $tareas
        ])->layout('layouts.app');
    }

    // Este método se ejecuta cuando cambia el filtro de fecha, para refrescar la lista.
    public function updatedFiltroFecha()
    {
        $this->resetPage(); // Resetea la paginación al cambiar de fecha.
    }
}