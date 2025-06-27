<?php

namespace App\Livewire;

use Livewire\Component;
use Request;

class UserLivewire extends Component
{
    public function render()
    {
        return view('livewire.user-livewire')->layout('layouts.app');

    }
      
    //  public function guardarFoto(Request $request)
    //  {
    //      if ($request->hasFile('foto_operador') && $request->file('foto_operador')->isValid()) {
    //          $file = $request->file('foto_operador');
    //          $nombreArchivo = time() . '_' . $file->getClientOriginalName();
    //          $rutaArchivo = $file->storeAs('public/fotos_operadores', $nombreArchivo);

    //          // Guardar la ruta en la base de datos

    //          return back()->with('success', 'Foto subida correctamente.');
    //      } else {
    //          return back()->with('error', 'Error al subir la foto.');
    //      }
    //  }


}
