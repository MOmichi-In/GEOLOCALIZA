<?php

namespace App\Livewire\coordinador\gestion;

use Livewire\Component;
use App\Models\Actividad;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionActividades extends Component
{
    use WithPagination; // Habilita la paginación

    // --- PROPIEDADES ---
    // Estas son las variables que usaremos para conectar con los campos del formulario.
    public $actividad_id;
    public $nombre;

    // Estas son variables para controlar el estado de la interfaz.
    public $modalVisible = false; // Controla si el modal está abierto o cerrado.
    public $editando = false;     // Nos dice si el modal está en modo "Crear" o "Editar".
    public $searchTerm = '';      // Para el campo de búsqueda

    // --- MÉTODOS DEL CICLO DE VIDA ---
    
    // El método 'render' es el encargado de mostrar la vista y pasarle los datos.
    public function render()
    {
        // Busca actividades cuyo nombre contenga el término de búsqueda, las pagina y las pasa a la vista.
        $actividades = Actividad::where('nombre', 'like', '%' . $this->searchTerm . '%')
                                 ->latest()
                                 ->paginate(5); // Mostraremos 5 por página

        return view('livewire.coordinador.gestion.gestion-actividades', ['actividades' => $actividades])
               ->layout('layouts.app'); // Le decimos que use nuestra plantilla principal.
    }
    
    // --- LÓGICA DEL FORMULARIO ---
    
    // Define las reglas de validación para el formulario.
    protected function rules()
    {
        // La regla 'unique' es especial: al editar, debe ignorar el registro actual.
        // Si no, nos diría que el nombre ya existe (porque lo estamos editando).
        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('actividades')->ignore($this->actividad_id)],
        ];
    }
    
    // Se ejecuta cuando se pulsa el botón "Guardar" del modal.
    public function guardar()
    {
        $this->validate(); // Ejecuta la validación definida en rules().

        // updateOrCreate es un método de Laravel muy útil:
        // Si encuentra un registro con el 'id' proporcionado, lo actualiza.
        // Si no lo encuentra (o el id es null), crea un nuevo registro.
        Actividad::updateOrCreate(
            ['id' => $this->actividad_id],
            ['nombre' => $this->nombre]
        );

        // Envía un mensaje de éxito que se mostrará en la vista.
        session()->flash('message', $this->editando ? 'Actividad actualizada con éxito.' : 'Actividad creada con éxito.');
        
        $this->ocultarModal();
    }
    
    // --- MÉTODOS ACCIONADOS POR BOTONES ---
    
    // Se ejecuta cuando se pulsa el botón 'Editar'.
    public function editar($id)
    {
        $actividad = Actividad::findOrFail($id); // Busca la actividad o falla si no la encuentra.
        
        // Rellena las propiedades del componente con los datos de la actividad.
        $this->actividad_id = $actividad->id;
        $this->nombre = $actividad->nombre;
        $this->editando = true;
        
        $this->mostrarModal();
    }

    // Se ejecuta cuando se pulsa el botón 'Eliminar'.
    public function eliminar($id)
    {
        // Futura validación: No eliminar si la actividad está en uso por alguna tarea.
        Actividad::destroy($id);
        session()->flash('message', 'Actividad eliminada con éxito.');
    }
    
    // --- MÉTODOS DE UTILIDAD PARA EL MODAL ---
    
    // Se ejecuta al pulsar "Crear Nueva Actividad".
    public function mostrarModal()
    {
        $this->limpiarCampos();
        $this->modalVisible = true;
    }
    
    // Se ejecuta al pulsar "Cancelar" o la 'X'.
    public function ocultarModal()
    {
        $this->limpiarCampos();
        $this->modalVisible = false;
    }

    // Resetea las propiedades del formulario.
    private function limpiarCampos()
    {
        $this->reset(['actividad_id', 'nombre', 'editando']);
        $this->resetErrorBag(); // Limpia los mensajes de error de validación.
    }
}