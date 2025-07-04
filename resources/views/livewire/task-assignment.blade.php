<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900  leading-tight">
            {{ __('Asignación de Tareas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray dark:bg-white-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-1 text-gray-900 dark:text-black-100">
                    <div class="flex justify-between items-center mb-9">
                        <h3 class="text-2xl font-bold text-gray-800">Listado de tareas asignadas</h3>
                        <button wire:click="create()" class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-600">
                            Asignar Nueva Tarea
                        </button>
                    </div>

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    {{-- @error('duplicate')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ $message }}</span>
                        </div>
                    @enderror --}}


                    {{-- Modal para Crear/Editar Tarea --}}
                    @if ($isModalOpen)
                        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                            aria-modal="true">
                            <div
                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                    aria-hidden="true" wire:click="closeModal()"></div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                    aria-hidden="true">​</span>
                                <div
                                    class="inline-block align-bottom bg-white dark:bg-gray-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    <form wire:submit.prevent="store">
                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100"
                                                id="modal-title">
                                                {{ $isEditMode ? 'Editar Tarea' : 'Asignar Nueva Tarea' }}
                                            </h3>
                                            <div class="mt-4 space-y-4">
                                                <div>
                                                    <label for="fecha_trabajo"
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha
                                                        de Trabajo</label>
                                                    <input type="date" wire:model.lazy="fecha_trabajo"
                                                        id="fecha_trabajo"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md">
                                                    @error('fecha_trabajo')
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="ciclo"
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ciclo</label>
                                                    <input type="text" wire:model.lazy="ciclo" id="ciclo"
                                                        placeholder="Ej: Ciclo 1, C001"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md">
                                                    @error('ciclo')
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="correria"
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correría</label>
                                                    <input type="text" wire:model.lazy="correria" id="correria"
                                                        placeholder="Ej: Correría Norte, R05"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md">
                                                    @error('correria')
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="operador_id"
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Operador
                                                        Logístico</label>
                                                    <select wire:model="operador_id" id="operador_id"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md">
                                                        <option value="">Seleccione un Operador</option>
                                                        @foreach ($operadores as $operador)
                                                            <option value="{{ $operador->id }}">{{ $operador->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('operador_id')
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                @if ($selectedOperadorUnidadTrabajo)
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">Unidad de
                                                            Trabajo: <span
                                                                class="font-semibold">{{ $selectedOperadorUnidadTrabajo }}</span>
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="submit"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                {{ $isEditMode ? 'Actualizar Tarea' : 'Guardar Tarea' }}
                                            </button>
                                            <button type="button" wire:click="closeModal()"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Tabla de Tareas Asignadas --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-black dark:text-black-300 uppercase tracking-wider">
                                        Fecha Trabajo</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-black dark:text-black-300 uppercase tracking-wider">
                                        Ciclo</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-black dark:text-black-300 uppercase tracking-wider">
                                        Correría</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-black dark:text-black-300 uppercase tracking-wider">
                                        Operador</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium  text-black dark:text-black-300 uppercase tracking-wider">
                                        Unidad Trabajo</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium  text-black dark:text-black-300 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-white-100 divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($tareasPaginadas as $tarea)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $tarea->fecha_trabajo->format('d/m/Y') }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $tarea->ciclo }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $tarea->correria }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $tarea->operador->name ?? 'N/A' }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $tarea->operador->unidadTrabajo->nombre ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="edit({{ $tarea->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">Editar</button>
                                            <button wire:click="delete({{ $tarea->id }})"
                                                wire:confirm="¿Estás seguro de que quieres eliminar esta tarea asignada?"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Eliminar</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-center text-black-200 dark:text-black-00">
                                            No hay tareas asignadas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tareasPaginadas->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
