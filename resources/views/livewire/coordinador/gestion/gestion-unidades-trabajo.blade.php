<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Gestión de Unidades de Trabajo') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Unidades Registradas</h3>
                <button wire:click="crear()" class="bg-red-700 hover:bg-red-800 text-white px-4 py-3 rounded-lg font-semibold transition">
                    Crear Nueva Unidad
                </button>
            </div>
            
            <div class="mb-4">
                 <input wire:model.live.debounce.300ms="searchTerm" type="text" placeholder="Buscar por nombre de unidad..." class="w-full lg:w-1/3 p-2 border border-gray-300 rounded-lg">
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre Unidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supervisor a Cargo</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Operadores</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($unidades as $unidad)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $unidad->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $unidad->supervisor->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center font-bold">{{ $unidad->operadores_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button wire:click="editar({{ $unidad->id }})" class="bg-gray-400 hover:bg-gray-600 text-white px-3 py-1 rounded">
                                        Gestionar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-4">No hay unidades de trabajo registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($unidades->hasPages())
                <div class="mt-6">
                    {{ $unidades->links() }}
                </div>
            @endif
        </div>
    </div>

    @if ($isOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative">
        <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl font-bold">×</button>

        <h2 class="text-xl font-bold mb-4 text-center">
            {{ $unidad_id ? 'Gestionar Unidad de Trabajo' : 'Crear Nueva Unidad' }}
        </h2>

        <form wire:submit.prevent="store" class="space-y-4">
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
            @endif

            <div class="flex justify-between items-center pt-4">
                <div>
                    @if ($unidad_id)
                    <button type="button" 
                            wire:click="deleteUnit" 
                            wire:confirm="¿Estás seguro de ELIMINAR esta unidad? Esta acción no se puede deshacer."
                            class="bg-gray-400 hover:bg-gray-600 text-white px-5 py-2 rounded text-sm transition">
                        Eliminar Unidad
                    </button>
                    @endif
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded transition">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-red-800 hover:bg-red-700 text-white px-5 py-2 rounded transition">
                        {{ $unidad_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
    @endif
</div>