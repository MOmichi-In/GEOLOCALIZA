<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            Gestionar Tarea #{{ $tarea->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Resumen de la Tarea (Sección no editable) --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Resumen de la Asignación</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <span class="text-sm text-gray-500">Operador</span>
                        <p class="font-semibold">{{ $tarea->operador->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-600">C.C: {{ $tarea->operador->cedula ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Actividad</span>
                        <p class="font-semibold">{{ $tarea->actividad->nombre ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Ciclo / Correría</span>
                        <p class="font-semibold">{{ $tarea->ciclo->nombre ?? 'N/A' }} /
                            {{ $tarea->correria->nombre ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Estado</span>
                        <p>
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if ($tarea->estado == 'Asignada') bg-blue-100 text-blue-800
                                @elseif($tarea->estado == 'Finalizada') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $tarea->estado }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Formulario de Gestión y Firmas --}}
            <form wire:submit.prevent="finalizarTarea">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Registro de Entrega</h3>

                    {{-- Campos de Fecha y Observaciones --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="fecha_entrega" class="block text-sm font-medium text-gray-700">Fecha de Entrega
                                / Finalización</label>
                            <input type="date" wire:model="fecha_entrega" id="fecha_entrega"
                                class="mt-1 w-full p-2 border border-gray-300 rounded-lg">
                            @error('fecha_entrega')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="observaciones"
                                class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea wire:model.lazy="observaciones" id="observaciones" rows="3"
                                class="mt-1 w-full p-2 border border-gray-300 rounded-lg"></textarea>
                            @error('observaciones')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Sección de Firmas --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Firma de Inicio -->
                        <div class="text-center">
                            <h4 class="font-semibold mb-2">Firma de Inicio (Operador)</h4>
                            <div
                                class="border rounded-lg p-2 min-h-[12rem] flex items-center justify-center bg-gray-50">
                                @if ($firma_inicio_data)
                                    <img src="{{ $firma_inicio_data }}" alt="Firma de Inicio" class="max-w-full h-auto">
                                @else
                                    <span class="text-gray-400">Sin firma</span>
                                @endif
                            </div>
                            <button type="button" wire:click="abrirModalFirma('firma_inicio_data')"
                                class="text-sm text-indigo-600 hover:text-indigo-800 mt-2">
                                {{ $firma_inicio_data ? 'Cambiar Firma' : 'Añadir Firma' }}
                            </button>
                            @error('firma_inicio_data')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Firma Final -->
                        <div class="text-center">
                            <h4 class="font-semibold mb-2">Firma Final (Operador)</h4>
                            <div
                                class="border rounded-lg p-2 min-h-[12rem] flex items-center justify-center bg-gray-50">
                                @if ($firma_final_data)
                                    <img src="{{ $firma_final_data }}" alt="Firma Final" class="max-w-full h-auto">
                                @else
                                    <span class="text-gray-400">Sin firma</span>
                                @endif
                            </div>
                            <button type="button" wire:click="abrirModalFirma('firma_final_data')"
                                class="text-sm text-indigo-600 hover:text-indigo-800 mt-2">
                                {{ $firma_final_data ? 'Cambiar Firma' : 'Añadir Firma' }}
                            </button>
                            @error('firma_final_data')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Firma Supervisor -->
                        <div class="text-center">
                            <h4 class="font-semibold mb-2">Firma del Supervisor</h4>
                            <div
                                class="border rounded-lg p-2 min-h-[12rem] flex items-center justify-center bg-gray-50">
                                @if ($firma_supervisor_data)
                                    <img src="{{ $firma_supervisor_data }}" alt="Firma Supervisor"
                                        class="max-w-full h-auto">
                                @else
                                    <span class="text-gray-400">Sin firma</span>
                                @endif
                            </div>
                            <button type="button" wire:click="abrirModalFirma('firma_supervisor_data')"
                                class="text-sm text-indigo-600 hover:text-indigo-800 mt-2">
                                {{ $firma_supervisor_data ? 'Cambiar Firma' : 'Añadir Firma' }}
                            </button>
                            @error('firma_supervisor_data')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                  
                    {{-- Ahora empieza el bloque de botones FUERA del formulario --}}
                    <div class="flex justify-end mt-8 border-t pt-6 space-x-4">
                        <a href="{{ route('panel.tareas') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                            Volver al Panel
                        </a>

                        <button type="button" wire:click="guardarTarea"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                            Guardar
                        </button>

                        <button type="button" wire:click="finalizarTarea"
                            class="bg-red-700 hover:bg-red-800 text-white px-6 py-3 rounded-lg font-semibold transition">
                            Finalizar Tarea
                        </button>

                        <button type="button" wire:click="descargarPdf"
                            class="bg-indigo-700 hover:bg-indigo-800 text-white px-6 py-3 rounded-lg font-semibold transition">
                            Descargar PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PARA LA FIRMA (Reutilizable) --}}
    @if ($modalFirmaVisible)
        <div x-data="signatureModal()" x-init="initPad()"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
                <h3 class="font-bold text-lg mb-4 text-center">Por favor, firme en el recuadro</h3>
                <div class="border rounded-lg">
                    <canvas x-ref="pad" class="w-full h-48 bg-gray-100 rounded"></canvas>
                </div>
                <div class="flex justify-between mt-4 space-x-2">
                    <button type="button" @click="clearPad"
                        class="bg-gray-400 text-white px-4 py-2 rounded w-full">Limpiar</button>
                    <button type="button" @click="save"
                        class="bg-red-700 text-white px-4 py-2 rounded w-full">Guardar
                        Firma</button>
                    <button type="button" @click="$wire.set('modalFirmaVisible', false)"
                        class="bg-gray-600 text-white px-4 py-2 rounded w-full">Cancelar</button>
                </div>

            </div>
        </div>
    @endif

</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        function signatureModal() {
            return {
                signaturePad: null,
                initPad() {
                    // Pequeño retraso para asegurar que el canvas es visible en el DOM de Alpine
                    this.$nextTick(() => {
                        this.signaturePad = new SignaturePad(this.$refs.pad, {
                            backgroundColor: null // bg-gray-100
                        });
                    });
                },
                clearPad() {
                    if (this.signaturePad) {
                        this.signaturePad.clear();
                    }
                },
                save() {
                    if (this.signaturePad && !this.signaturePad.isEmpty()) {
                        const dataUrl = this.signaturePad.toDataURL('image/png');
                        // Llama al método del backend y le pasa la firma en Base64
                        @this.call('guardarFirma', dataUrl);
                    } else {
                        alert("Por favor, proporcione una firma antes de guardar.");
                    }
                }
            }
        }
    </script>
@endpush
