<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Asignación de Tareas') }}
        </h2>
    </x-slot>

    {{-- Notificaciones Flash --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
        @endif
    </div>

    {{-- Contenido Principal --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Tareas Programadas</h3>
                <button wire:click="create()" class="bg-red-700 hover:bg-red-800 text-white px-4 py-3 rounded-lg font-semibold transition">
                    Asignar Nueva Tarea
                </button>
            </div>

            {{-- Tabla de Tareas --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Operador</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supervisor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ciclo/Correría</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tareasPaginadas as $tarea)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tarea->fecha_trabajo->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tarea->operador->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tarea->operador->unidadTrabajo->nombre ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tarea->operador->supervisor->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tarea->ciclo }} / {{ $tarea->correria }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="edit({{ $tarea->id }})" class="bg-gray-400 hover:bg-gray-600 text-white px-3 py-1 rounded">Editar</button>
                                        <button wire:click="delete({{ $tarea->id }})" wire:confirm="¿Seguro que quieres eliminar esta tarea?" class="bg-red-800 hover:bg-red-700 text-white px-3 py-1 rounded">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-4">No hay tareas asignadas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tareasPaginadas->hasPages())
                <div class="mt-6">{{ $tareasPaginadas->links() }}</div>
            @endif
        </div>
    </div>

    {{-- MODAL --}}
    @if ($isModalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative">
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl font-bold">×</button>
                <h2 class="text-xl font-bold mb-4 text-center">{{ $isEditMode ? 'Editar Tarea' : 'Asignar Nueva Tarea' }}</h2>

                <form wire:submit.prevent="store" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Fecha de Trabajo</label>
                            <input type="date" wire:model="fecha_trabajo" class="w-full px-4 py-2 border rounded-lg">
                            @error('fecha_trabajo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Ciclo</label>
                            <input type="text" wire:model="ciclo" class="w-full px-4 py-2 border rounded-lg" placeholder="Ej: Lectura 01">
                            @error('ciclo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Correría / Ruta</label>
                            <input type="text" wire:model="correria" class="w-full px-4 py-2 border rounded-lg" placeholder="Ej: Ruta 15-B">
                            @error('correria') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Operador Logístico</label>
                            <select wire:model.live="operador_id" class="w-full px-4 py-2 border rounded-lg">
                                <option value="">Seleccione un Operador</option>
                                @foreach ($operadores as $operador)
                                    <option value="{{ $operador->id }}">{{ $operador->name }}</option>
                                @endforeach
                            </select>
                            @error('operador_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Campos Automáticos -->
                    @if($operador_id)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg border grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Supervisor Asignado:</label>
                                <p class="font-semibold text-gray-800">{{ $supervisor_nombre ?: 'Cargando...' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Unidad de Trabajo:</label>
                                <p class="font-semibold text-gray-800">{{ $unidad_trabajo_nombre ?: 'Cargando...' }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Botones de Acción -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="submit" class="bg-red-800 hover:bg-red-700 text-white px-5 py-2 rounded">
                            {{ $isEditMode ? 'Actualizar Tarea' : 'Guardar Tarea' }}
                        </button>
                        <button type="button" wire:click="closeModal" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>