<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Asignación de Tareas') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Tareas Programadas</h3>
                <button wire:click="create()"
                    class="bg-red-700 hover:bg-red-800 text-white px-4 py-3 rounded-lg font-semibold transition">
                    Asignar Nueva Tarea
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">F. Inicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Operador</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actividad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ciclo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correría
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">F. Entrega</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tareasPaginadas as $tarea)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $tarea->fecha_inicio ? $tarea->fecha_inicio->format('d/m/y h:i a') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $tarea->operador->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $tarea->operador->cedula ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $tarea->actividad->nombre ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $tarea->ciclo->nombre ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm"> {{ $tarea->correria->nombre ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm"> {{ $tarea->cantidad ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $tarea->fecha_entrega ? $tarea->fecha_entrega->format('d/m/y') : '--' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($tarea->estado == 'Asignada') bg-blue-100 text-blue-800
                                        @elseif($tarea->estado == 'Finalizada') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $tarea->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="edit({{ $tarea->id }})"
                                            class="bg-gray-400 hover:bg-gray-600 text-white px-3 py-1 rounded">Editar</button>
                                               @if (Auth::user()->rol === 'Coordinador_Administrativo' || 'SUPER')
                                        <button wire:click="delete({{ $tarea->id }})" wire:confirm="¿Estás seguro?"
                                            class="bg-red-800 hover:bg-red-700 text-white px-3 py-1 rounded">Eliminar</button>
                                            @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-4">No hay tareas asignadas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($tareasPaginadas->hasPages())
                <div class="mt-6">{{ $tareasPaginadas->links() }}</div>
            @endif
        </div>
    </div>

    @if ($isModalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full p-6 relative">
                <button wire:click="closeModal"
                    class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl font-bold">×</button>
                <h2 class="text-xl font-bold mb-6 text-center">
                    {{ $isEditMode ? 'Editar Tarea Asignada' : 'Asignar Nueva Tarea' }}</h2>

                <form wire:submit.prevent="store" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Ciclo</label>
                            <select wire:model.live="ciclo_id" class="w-full px-4 py-2 border rounded-lg">
                                <option value="">Seleccione...</option>
                                @foreach ($ciclos as $ciclo)
                                    <option value="{{ $ciclo->id }}">{{ $ciclo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('ciclo_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Correría</label>
                            <select wire:model="correria_id" class="w-full px-4 py-2 border rounded-lg"
                                @if (empty($correrias)) disabled @endif>
                                <option value="">Seleccione...</option>
                                @foreach ($correrias as $correria)
                                    <option value="{{ $correria->id }}">{{ $correria->nombre }}</option>
                                @endforeach
                            </select>
                            @error('correria_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Actividad</label>
                            <select wire:model="actividad_id" class="w-full px-4 py-2 border rounded-lg">
                                <option value="">Seleccione...</option>
                                @foreach ($actividades as $actividad)
                                    <option value="{{ $actividad->id }}">{{ $actividad->nombre }}</option>
                                @endforeach
                            </select>
                            @error('actividad_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Operador Logístico</label>
                        <select wire:model.live="operador_id" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">Seleccione...</option>
                            @foreach ($operadores as $operador)
                                <option value="{{ $operador->id }}">{{ $operador->name }}</option>
                            @endforeach
                        </select>
                        @error('operador_id')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($operador_id)
                        <div class="p-4 bg-gray-50 rounded-lg border grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Cédula:</label>
                                <p class="font-semibold text-gray-800">{{ $operador_cedula ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Unidad:</label>
                                <p class="font-semibold text-gray-800">{{ $unidad_trabajo_nombre ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Supervisor:</label>
                                <p class="font-semibold text-gray-800">{{ $supervisor_nombre ?: 'N/A' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" wire:model="cantidad" id="cantidad" min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('cantidad')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="closeModal"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">Cancelar</button>
                        <button type="submit" class="bg-red-800 hover:bg-red-700 text-white px-5 py-2 rounded">
                            {{ $isEditMode ? 'Actualizar Tarea' : 'Asignar Tarea' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
