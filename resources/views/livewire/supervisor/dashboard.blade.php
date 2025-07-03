<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-black leading-tight">
            {{ __('Mis Tareas Asignadas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-00 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-00 dark:text-gray-100">

                    <div class="mb-4">
                        <label for="filtroFecha" class="block text-sm font-medium text-gray-900 dark:text-black-2200">Filtrar por Fecha:</label>
                        <input type="date" wire:model.live="filtroFecha" id="filtroFecha" class="mt-1 block w-full md:w-1/4 shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-white dark:text-gray-900 rounded-md">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-100">
                            <thead class="bg-gray-50 dark:bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 dark:text-black uppercase tracking-wider">Operador</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 dark:text-black uppercase tracking-wider">Ciclo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 dark:text-black uppercase tracking-wider">Correría</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 dark:text-black uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 dark:text-black uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white  divide-y divide-gray-100 dark:divide-gray-100">
                                @forelse ($tareas as $tarea)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $tarea->operador->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $tarea->ciclo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $tarea->correria }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $tarea->estado ?? 'Pendiente' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{-- Aquí irán los botones del Módulo 2 --}}
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Registrar Asistencia</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No hay tareas asignadas para la fecha seleccionada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tareas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>