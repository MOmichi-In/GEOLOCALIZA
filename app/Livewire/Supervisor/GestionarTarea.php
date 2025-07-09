<?php

namespace App\Livewire\Supervisor;

use App\Models\User;
use Livewire\Component;
use App\Models\TareaAsignada;
use Barryvdh\DomPDF\Facade\Pdf;

class GestionarTarea extends Component
{
    // --- PROPIEDADES PRINCIPALES ---
    public TareaAsignada $tarea;

    // --- PROPIEDADES BINDEADAS AL FORMULARIO ---
    public $fecha_entrega;
    public $observaciones;
    public $estado_tarea;

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
            'fecha_entrega' => 'required|date|after_or_equal:tarea.fecha_inicio',
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

        $this->tarea->update([
            'fecha_entrega' => $this->fecha_entrega,
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
        $this->validate([
            'fecha_entrega' => 'required|date|after_or_equal:tarea.fecha_inicio',
            'observaciones' => 'nullable|string',
            // Las firmas no son requeridas aquí
        ]);

        $this->tarea->update([
            'fecha_entrega' => $this->fecha_entrega,
            'observaciones' => $this->observaciones,
            'firma_inicio' => $this->firma_inicio_data,
            'firma_final' => $this->firma_final_data,
            'firma_supervisor' => $this->firma_supervisor_data,
            // NO cambia el estado
        ]);

        session()->flash('message', 'Cambios guardados correctamente.');
    }

    public function descargarPdf()
    {
        $tarea = $this->tarea;
        $pdf = Pdf::loadView('pdfs.gestionar-tarea', compact('tarea'))
            ->setPaper('a4', 'portrait');
        return response()->streamDownload(
            fn() => print ($pdf->output()),
            "tarea_{$tarea->id}.pdf"
        );
    }
}
