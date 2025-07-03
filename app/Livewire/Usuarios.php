<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Component
{
    public $usuarios;

    public $name, $email, $password, $rol = '', $user_id;
    public $editando = false;
    public $modalVisible = false;

    public function render()
    {
        $this->usuarios = User::all();
        return view('livewire.usuarios')->layout('layouts.app');
    }

    public function limpiarCampos()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->rol = '';
        $this->user_id = null;
        $this->editando = false;
    }

    public function mostrarModal()
    {
        $this->modalVisible = true;
    }

    public function ocultarModal()
    {
        $this->modalVisible = false;
        $this->limpiarCampos();
    }

    public function guardar()
    {
        if ($this->editando) {
            $this->actualizar();
            return;
        }

        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:Analista,Operador_Logistico,Coordinador_Administrativo,Supervisor',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'rol' => $this->rol,
        ]);

        session()->flash('message', 'Usuario creado con éxito.');
        $this->ocultarModal();
    }

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

    public function actualizar()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'rol' => 'required|in:Analista,Operador_Logistico,Coordinador_Administrativo,Supervisor',
        ]);

        $usuario = User::findOrFail($this->user_id);
        $usuario->update([
            'name' => $this->name,
            'email' => $this->email,
            'rol' => $this->rol,
            'password' => $this->password ? Hash::make($this->password) : $usuario->password,
        ]);

        session()->flash('message', 'Usuario actualizado con éxito.');
        $this->ocultarModal();
    }

    public function eliminar($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'Usuario eliminado con éxito.');
    }
}
