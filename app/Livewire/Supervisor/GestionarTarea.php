<?php

namespace App\Livewire\Supervisor;

use App\Models\User;
use Livewire\Component;
use App\Models\TareaAsignada;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class GestionarTarea extends Component
{
    // --- PROPIEDADES PRINCIPALES ---
    public TareaAsignada $tarea;

    // --- PROPIEDADES BINDEADAS AL FORMULARIO ---
    public $fecha_entrega;
    public $observaciones;
    public $estado_tarea;

    public $datosModificados = false;


    // --- FIRMAS ---
    public $firma_inicio_data;
    public $firma_final_data;
    public $firma_supervisor_data;

    // --- MODAL ---
    public $modalFirmaVisible = false;
    public $firmaTarget = '';

    // --- VALIDACIÓN ---
    protected function rules()
    {
        return [
            'fecha_entrega' => 'required|date',
            'observaciones' => 'nullable|string',
            'firma_inicio_data' => 'required_without:tarea.firma_inicio',
            'firma_final_data' => 'required_without:tarea.firma_final',
            'firma_supervisor_data' => 'required_without:tarea.firma_supervisor',
        ];
    }




    protected $messages = [
        'required_without' => 'Se requiere una firma para finalizar.',
        'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser anterior a la de inicio.'
    ];


    public function updated($property)
    {
        $this->datosModificados = true;
    }

    public function mount(TareaAsignada $tarea)
    {
        if (
            auth()->id() != $tarea->supervisor_id &&
            auth()->user()->rol !== User::ROLE_COORDINADOR_ADMINISTRATIVO
        ) {
            abort(403, 'Acceso No Autorizado');
        }

        $this->tarea = $tarea;
        $this->fecha_entrega = $tarea->fecha_entrega ? $tarea->fecha_entrega->format('Y-m-d') : '';
        $this->observaciones = $tarea->observaciones;
        $this->estado_tarea = $tarea->estado;
        $this->firma_inicio_data = $tarea->firma_inicio;
        $this->firma_final_data = $tarea->firma_final;
        $this->firma_supervisor_data = $tarea->firma_supervisor;
    }

    public function render()
    {
        return view('livewire.supervisor.gestionar-tarea')->layout('layouts.app');
    }

    public function abrirModalFirma($target)
    {
        $this->firmaTarget = $target;
        $this->modalFirmaVisible = true;
        $this->dispatch('abrirModalFirma');
    }

    public function guardarFirma($signatureData)
    {
        if ($this->firmaTarget) {
            $this->{$this->firmaTarget} = $signatureData;
        }
        $this->modalFirmaVisible = false;
    }

    public function finalizarTarea()
    {
        $this->validate();

        $this->dispatch('tareaFinalizada');

        if ($this->tarea->fecha_inicio && $this->fecha_entrega < $this->tarea->fecha_inicio->format('Y-m-d')) {
            $this->addError('fecha_entrega', 'La fecha de entrega no puede ser anterior a la fecha de inicio.');
            return;
        }

        $this->tarea->update([
            'fecha_entrega' => now(),
            'observaciones' => $this->observaciones,
            'estado' => 'Finalizada',
            'firma_inicio' => $this->firma_inicio_data,
            'firma_final' => $this->firma_final_data,
            'firma_supervisor' => $this->firma_supervisor_data,
        ]);

        session()->flash('message', 'Tarea finalizada y registrada correctamente.');
        return $this->redirect(route('panel.tareas'), navigate: true);
    }



    /**
     * Guarda sin finalizar la tarea (no cambia el estado).
     */
    public function guardarTarea()
    {
        $this->dispatch('tareaGuardada');

        $this->validate([
            'fecha_entrega' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        if ($this->tarea->fecha_inicio && $this->fecha_entrega < $this->tarea->fecha_inicio->format('Y-m-d')) {
            $this->addError('fecha_entrega', 'La fecha de entrega no puede ser anterior a la fecha de inicio.');
            return;
        }



        $this->tarea->update([
            'fecha_entrega' => $this->fecha_entrega,
            'observaciones' => $this->observaciones,
            'firma_inicio' => $this->firma_inicio_data,
            'firma_final' => $this->firma_final_data,
            'firma_supervisor' => $this->firma_supervisor_data,
        ]);

        $this->datosModificados = false;

        session()->flash('message', 'Cambios guardados correctamente.');
    }


    public function descargarPdf()
    {
        if ($this->tarea->estado !== 'Finalizada') {
            session()->flash('error', 'Solo puedes descargar el PDF cuando la tarea esté finalizada.');
            return;
        }

        $tarea = $this->tarea;
        $pdf = Pdf::loadView('pdfs.gestionar-tarea', compact('tarea'))
            ->setPaper('a4', 'portrait');
        return response()->streamDownload(
            fn() => print ($pdf->output()),
            "tarea_{$tarea->id}.pdf"
        );
    }

}
