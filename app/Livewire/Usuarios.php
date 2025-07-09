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
    public $cedula; // <-- CAMBIO: Añadida nueva propiedad para la cédula

    // Propiedades de estado
    public $modalVisible = false;
    public $editando = false;

    // El método principal de renderizado
    public function render()
    {
        // Se paginan los resultados para mejor rendimiento
        $usuarios = User::latest()->paginate(10);
        return view('livewire.usuarios', ['usuarios' => $usuarios])->layout('layouts.app');
    }

    // Unifica las reglas de validación para crear y editar
    private function validationRules()
    {
        $userIdForUniqueRule = $this->editando ? $this->user_id : null;
        
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($userIdForUniqueRule)],
            'rol' => ['required', Rule::in(User::$availableRoles)],
            // La contraseña es opcional al editar
            'password' => $this->editando ? 'nullable|min:6' : 'required|min:6',
            // <-- CAMBIO: Se añade la validación para la cédula
            'cedula' => ['required', 'string', 'min:5', Rule::unique('users')->ignore($userIdForUniqueRule)],
        ];
    }

    public function guardar()
    {
        // Usa las reglas de validación unificadas
        $this->validate($this->validationRules());
        
        // El guardado ahora decide qué método interno llamar
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
            'cedula' => $this->cedula, // <-- CAMBIO: Se incluye la cédula
        ];

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
        $this->cedula = $usuario->cedula; // <-- CAMBIO: Se carga la cédula del usuario
        $this->editando = true;
        $this->modalVisible = true;
    }

    // Método privado para la lógica de ACTUALIZACIÓN
    private function actualizar()
    {
        $usuario = User::findOrFail($this->user_id);
        $rolOriginal = $usuario->rol;

        $datosUsuario = [
            'name' => $this->name,
            'email' => $this->email,
            'rol' => $this->rol,
            'cedula' => $this->cedula, // <-- CAMBIO: Se incluye la cédula
        ];

        // Lógica de promoción para código de supervisor
        if ($this->rol === User::ROLE_SUPERVISOR && $rolOriginal !== User::ROLE_SUPERVISOR && empty($usuario->codigo_supervisor)) {
            $ultimoCodigo = (int) User::where('rol', User::ROLE_SUPERVISOR)->max('codigo_supervisor');
            $datosUsuario['codigo_supervisor'] = str_pad($ultimoCodigo + 1, 4, '0', STR_PAD_LEFT);
        }

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
        $this->resetErrorBag(); // También limpia los mensajes de error
    }
}