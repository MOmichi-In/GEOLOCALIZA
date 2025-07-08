<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class Usuarios extends Component
{
    use WithPagination;

    // Propiedades del formulario
    public $user_id;
    public $name, $email, $password, $rol = '';

    // Propiedades de estado
    public $modalVisible = false;
    public $editando = false;

    // El método principal de renderizado
    public function render()
    {
        return view('livewire.usuarios', [
            'usuarios' => User::latest()->paginate(10)
        ])->layout('layouts.app');
    }

    // Este es el método que llama el botón "Guardar" del formulario
    public function guardar()
    {
        // Define las reglas de validación comunes para crear y editar
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user_id)],
            'rol' => ['required', Rule::in(User::$availableRoles)],
            // La contraseña solo es obligatoria si no estamos editando (es decir, al crear)
            'password' => Rule::requiredIf(!$this->editando),
        ]);

        // Decide qué acción tomar basado en si estamos en modo edición o no
        if ($this->editando) {
            $this->actualizar();
        } else {
            $this->crear();
        }
    }

    // Método privado para la lógica de CREACIÓN
    private function crear()
    {
        $datosUsuario = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'rol' => $this->rol,
        ];

        // La "magia" para generar el código de supervisor solo al crear
        if ($this->rol === User::ROLE_SUPERVISOR) {
            $ultimoCodigo = (int) User::where('rol', User::ROLE_SUPERVISOR)->max('codigo_supervisor');
            $datosUsuario['codigo_supervisor'] = str_pad($ultimoCodigo + 1, 4, '0', STR_PAD_LEFT);
        }

        User::create($datosUsuario);
        session()->flash('message', 'Usuario creado con éxito.');
        $this->ocultarModal();
    }

    // Método para preparar el formulario para EDICIÓN
    public function editar($id)
    {
        $usuario = User::findOrFail($id);
        $this->user_id = $usuario->id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->rol = $usuario->rol;
        $this->editando = true;
        $this->modalVisible = true;
    }

    // Método privado para la lógica de ACTUALIZACIÓN
    // En app/Livewire/Usuarios.php

    // Este método solo se encarga de ACTUALIZAR
    private function actualizar()
    {
        $usuario = User::findOrFail($this->user_id);

        // Guardamos el rol original antes de hacer cualquier cambio
        $rolOriginal = $usuario->rol;

        // Preparamos los datos a actualizar
        $datosUsuario = [
            'name' => $this->name,
            'email' => $this->email,
            'rol' => $this->rol,
        ];

        // *** LA LÓGICA DE PROMOCIÓN ESTÁ AQUÍ ***
        // Comprobamos si el usuario está siendo ascendido a Supervisor
        if ($this->rol === User::ROLE_SUPERVISOR && $rolOriginal !== User::ROLE_SUPERVISOR) {

            // Si es así, y si no tiene ya un código de supervisor, se lo generamos.
            if (empty($usuario->codigo_supervisor)) {
                $ultimoCodigo = (int) User::where('rol', User::ROLE_SUPERVISOR)->max('codigo_supervisor');
                $datosUsuario['codigo_supervisor'] = str_pad($ultimoCodigo + 1, 4, '0', STR_PAD_LEFT);
            }
        }

        // Si lo están degradando (deja de ser supervisor), podríamos querer quitar su código. Opcional.
        // if ($this->rol !== User::ROLE_SUPERVISOR && $rolOriginal === User::ROLE_SUPERVISOR) {
        //     $datosUsuario['codigo_supervisor'] = null;
        // }

        // Solo se actualiza la contraseña si se ha escrito una nueva
        if (!empty($this->password)) {
            $datosUsuario['password'] = Hash::make($this->password);
        }

        $usuario->update($datosUsuario);
        session()->flash('message', 'Usuario actualizado con éxito.');
        $this->ocultarModal();
    }

    // Método para ELIMINAR un usuario
    public function eliminar($id)
    {
        // Las validaciones de si el usuario está en uso se harán en módulos futuros para mantener este CRUD simple.
        User::find($id)->delete();
        session()->flash('message', 'Usuario eliminado.');
    }

    // --- Métodos de Utilidad ---
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
        $this->reset();
    }
}