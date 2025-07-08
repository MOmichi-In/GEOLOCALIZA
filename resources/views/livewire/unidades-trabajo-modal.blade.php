<!-- Modal de Gestión de Unidades -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative">
        <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl font-bold">×</button>

        <h2 class="text-xl font-bold mb-4 text-center">
            {{ $unidad_id ? 'Gestionar Unidad de Trabajo' : 'Crear Nueva Unidad' }}
        </h2>

        <form wire:submit.prevent="store" class="space-y-4">
            <!-- Fila 1: Nombre y Supervisor -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Nombre de la Unidad</label>
                    <input type="text" wire:model.defer="nombre" class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500">
                    @error('nombre') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Supervisor a Cargo</label>
                    <select wire:model.defer="supervisor_id_actual" class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500">
                        <option value="">-- Sin Asignar --</option>
                        @foreach($supervisores as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                    @error('supervisor_id_actual') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Fila 2: Lista de Operadores (Solo en modo edición) -->
            @if ($unidad_id)
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1">Asociar Operadores</label>
                    <div class="h-64 overflow-y-auto border rounded-lg p-2 space-y-2">
                        @forelse ($todosLosOperadores as $operador)
                            @php
                                // Un operador está deshabilitado si ya está asignado a OTRA unidad diferente a la actual
                                $estaEnOtraUnidad = $operador->unidad_trabajo_id && $operador->unidad_trabajo_id != $unidad_id;
                            @endphp
                            <label class="flex items-center p-2 rounded-md {{ $estaEnOtraUnidad ? 'bg-gray-100 opacity-60 cursor-not-allowed' : 'hover:bg-gray-50' }}">
                                <input type="checkbox" 
                                       value="{{ $operador->id }}" 
                                       wire:model.defer="operadoresAsignados" 
                                       @if($estaEnOtraUnidad) disabled @endif
                                       class="rounded text-red-600 shadow-sm focus:ring-red-500">
                                <span class="ml-3 text-gray-800">{{ $operador->name }}</span>
                                @if($estaEnOtraUnidad)
                                    <span class="ml-auto text-xs text-red-500 font-semibold">(en: {{ $operador->unidadTrabajo->nombre ?? 'Otra unidad' }})</span>
                                @endif
                            </label>
                        @empty
                            <p class="text-center text-sm text-gray-500 py-4">No hay operadores logísticos registrados.</p>
                        @endforelse
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-700 text-base font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ $unidad_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button type="button" wire:click="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                    @endif
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded transition duration-150 ease-in-out">Cancelar</button>
                    <button type="submit" class="bg-red-800 hover:bg-red-700 text-white px-5 py-2 rounded transition duration-150 ease-in-out">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>