<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use App\Imports\YourExcelImport; 


class Importacion extends Component
{
    use WithFileUploads;

    public $file;
    public $importing = false;
    public $message = '';
    public $messageType = '';

   
    public $filterRuta = '';
    public $filterDireccion = '';
    public $filterPropietario = '';



    protected $rules = [
        'file' => 'required|mimes:xlsx,xls,csv|max:10240',
    ];

    protected $messages = [
        'file.required' => 'Por favor, selecciona un archivo.',
        'file.mimes' => 'El archivo debe ser de tipo: xlsx, xls o csv.',
        'file.max' => 'El tamaño máximo del archivo es de 10 MB.',
    ];

    public function import()
    {
        $this->resetMessages(); 
        $this->importing = true; 

        try {
            $this->validate(); 
            
            Excel::import(new YourExcelImport, $this->file);

            $this->message = 'Archivo Excel importado correctamente. Los registros se han cargado.';
            $this->messageType = 'success';
            $this->file = null; 

        } catch (ValidationException $e) {
            
            $this->message = implode('<br>', collect($e->errors())->flatten()->toArray());
            $this->messageType = 'error';
        } catch (\Exception $e) {
        
            $this->message = 'Error al importar el archivo: ' . $e->getMessage();
            $this->messageType = 'error';
        } finally {
            $this->importing = false;
        }
    }

    public function resetMessages()
    {
        $this->message = '';
        $this->messageType = '';
    }
   
    public function getRecordsProperty()
    {
    
        $query = RegistroImportado::query();

     
        if ($this->filterRuta) {
            $query->where('ruta', 'like', '%' . $this->filterRuta . '%');
        }

        if ($this->filterDireccion) {
            $query->where('direccion', 'like', '%' . $this->filterDireccion . '%');
        }

        if ($this->filterPropietario) {
            $query->where('nombre_propietario', 'like', '%' . $this->filterPropietario . '%');
        }   
        return $query->latest()->get(); 
    }

    public function render()
    {
      
        return view('livewire.importacion')
                    ->layout('layouts.app', [
                        'header' => 'Panel de Importación' // Este se pasa a la ranura 'header' de layouts.app
                    ]);
    }
}