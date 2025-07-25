<?php

namespace App\Livewire\coordinador\usuarios;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class Usuarios extends Component
{
    use WithPagination;

    // Propiedades del formulario
    public $user_id; // Este DEBE ser null cuando no se está editando
    public $name, $email, $password, $rol = '';
    public $cedula;

    // Propiedades de estado
    public $modalVisible = false;
    public $editando = false; // Indica si estamos en modo edición o creación

    // El método principal de renderizado
    public function render()
    {
        $usuarios = User::latest()->paginate(10);
        return view('livewire.coordinador.usuarios.usuarios', ['usuarios' => $usuarios])->layout('layouts.app');
    }

    // Unifica las reglas de validación para crear y editar
    private function validationRules()
    {
        // La regla unique necesita saber el ID del usuario actual si estamos editando
        $userIdForUniqueRule = $this->editando ? $this->user_id : null;
        
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'email', 
                Rule::unique('users')->ignore($userIdForUniqueRule), // Ignora el email del usuario actual al editar
            ],
            'rol' => ['required', Rule::in(User::$availableRoles)],
            // La contraseña es opcional al editar, pero requerida al crear
            'password' => $this->editando ? 'nullable|min:6' : 'required|min:6',
            'cedula' => [
                'required', 
                'string', 
                'min:5', 
                Rule::unique('users')->ignore($userIdForUniqueRule), // Ignora la cédula del usuario actual al editar
            ],
        ];
    }

    public function guardar()
    {
        // Valida los datos según si estamos creando o editando
        $this->validate($this->validationRules());
        
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
            'cedula' => $this->cedula,
        ];

        if ($this->rol === User::ROLE_SUPERVISOR) {
            $ultimoCodigo = (int) User::where('rol', User::ROLE_SUPERVISOR)->max('codigo_supervisor');
            $datosUsuario['codigo_supervisor'] = str_pad($ultimoCodigo + 1, 4, '0', STR_PAD_LEFT);
        }

        User::create($datosUsuario);
        session()->flash('message', 'Usuario creado con éxito.');
        $this->ocultarModal(); // Esto limpiará el formulario y reseteará `editando` a false
        
        // Forzar la paginación a la primera página para refrescar la tabla con el nuevo usuario
        $this->gotoPage(1); 
    }

    // Método para preparar el formulario para EDICIÓN
    public function editar($id)
    {
        // Asegúrate de limpiar los campos y el estado antes de cargar los nuevos datos
        $this->limpiarCampos(); 

        $usuario = User::findOrFail($id);
        $this->user_id = $usuario->id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->rol = $usuario->rol;
        $this->cedula = $usuario->cedula;
        $this->editando = true; // Establecer a true para modo edición
        $this->modalVisible = true;
        // No cargamos la contraseña por seguridad, el campo queda en blanco
        $this->password = ''; 
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
            'cedula' => $this->cedula,
        ];

        // Lógica para asignar código de supervisor si el rol cambia a supervisor y no tiene uno
        if ($this->rol === User::ROLE_SUPERVISOR && $rolOriginal !== User::ROLE_SUPERVISOR && empty($usuario->codigo_supervisor)) {
            $ultimoCodigo = (int) User::where('rol', User::ROLE_SUPERVISOR)->max('codigo_supervisor');
            $datosUsuario['codigo_supervisor'] = str_pad($ultimoCodigo + 1, 4, '0', STR_PAD_LEFT);
        }

        // Solo se actualiza la contraseña si se ha escrito una nueva en el formulario
        if (!empty($this->password)) {
            $datosUsuario['password'] = Hash::make($this->password);
        }

        $usuario->update($datosUsuario);
        session()->flash('message', 'Usuario actualizado con éxito.');
        $this->ocultarModal(); // Esto limpiará el formulario y reseteará `editando` a false
        
        // Forzar la paginación a la primera página para refrescar la tabla
        $this->gotoPage(1); 
    }

    // Método para ELIMINAR un usuario
    public function eliminar($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Usuario eliminado.');
        
        // Forzar la paginación a la primera página para refrescar la tabla
        $this->gotoPage(1);
    }

    // --- Métodos de Utilidad ---
    public function mostrarModal()
    {
        // Al mostrar el modal para crear, asegúrate de que el estado es de creación
        $this->limpiarCampos(); // Limpia todos los campos del formulario
        $this->editando = false; // Establece el modo a creación
        $this->modalVisible = true;
    }

    public function ocultarModal()
    {
        $this->limpiarCampos(); // Limpia los campos y resetea el estado `editando`
        $this->modalVisible = false;
    }
    
    private function limpiarCampos()
    {
        $this->user_id = null; // MUY IMPORTANTE: Asegúrate de que el ID del usuario se resetea
        $this->name = '';
        $this->email = '';
        $this->password = ''; // Vaciar la contraseña
        $this->rol = '';
        $this->cedula = '';
        $this->editando = false; // MUY IMPORTANTE: Asegúrate de que el modo edición se resetea a false
        $this->resetErrorBag(); // También limpia los mensajes de error de validación
    }
}