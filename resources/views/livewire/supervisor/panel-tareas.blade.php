<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            @if (auth()->user()->rol === \App\Models\User::ROLE_SUPERVISOR)
                {{ __('Tareas de Mi Equipo') }}
            @else
                {{ __('Panel de Supervisión de Tareas') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                {{-- Sección de Filtros --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="text-sm font-medium">Buscar Operador:</label>
                        <input type="text" wire:model.live.debounce.300ms="searchTerm" class="w-full p-2 border ...">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Fecha de Inicio:</label>
                        <input type="date" wire:model.live="filtroFecha" class="w-full p-2 border ...">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Estado:</label>
                        <select wire:model.live="filtroEstado" class="w-full p-2 border ...">
                            <option value="">Todos</option>
                            <option value="Asignada">Asignada</option>
                            <option value="Finalizada">Finalizada</option>
                        </select>
                    </div>
                    @if (auth()->user()->rol === \App\Models\User::ROLE_COORDINADOR_ADMINISTRATIVO)
                        <div>
                            <label class="text-sm font-medium">Filtrar por Supervisor:</label>
                            <select wire:model.live="filtroSupervisor" class="w-full p-2 border ...">
                                <option value="">Todos los Supervisores</option>
                                @foreach ($supervisores as $supervisor)
                                    <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto ...">
                    <table class="min-w-full ...">
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- ¡OJO! CAMBIO IMPORTANTE EN LA VISTA PARA ENLAZAR A LA RUTA CORRECTA --}}
                                <th class="px-6 py-3 ...">Operador</th>
                                <th class="px-6 py-3 ...">Supervisor</th>
                                <th class="px-6 py-3 ...">Actividad</th>
                                <th class="px-6 py-3 ...">Estado</th>
                                <th class="px-6 py-3 ...">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tareas as $tarea)
                                <tr>
                                    {{-- Operador --}}
                                    <td class="px-6 py-4">{{ $tarea->operador->name ?? 'N/A' }}</td>

                                    {{-- Supervisor --}}
                                    <td class="px-6 py-4">{{ $tarea->supervisor->name ?? 'N/A' }}</td>

                                    {{-- Actividad --}}
                                    <td class="px-6 py-4">{{ $tarea->actividad->nombre ?? 'N/A' }}</td>

                                    {{-- Estado --}}
                                    <td class="px-6 py-4">
                                        @if ($tarea->estado === 'Asignada')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Asignada
                                            </span>
                                        @elseif ($tarea->estado === 'Finalizada')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Finalizada
                                            </span>
                                        @else
                                            {{ $tarea->estado ?? 'Sin estado' }}
                                        @endif
                                    </td>

                                    {{-- Acción --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('supervisor.tarea.gestionar', $tarea->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium">Gestionar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">No se encontraron tareas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                {{-- ... paginación ... --}}
            </div>
        </div>
    </div>
</div>
