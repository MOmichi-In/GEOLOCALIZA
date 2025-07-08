<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Gestión de Unidades de Trabajo') }}
        </h2>
    </x-slot>

    {{-- Contenedor de Notificaciones Flash --}}
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

    {{-- Contenido Principal de la Vista --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            {{-- Encabezado de la sección con el botón de Crear --}}
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Unidades Registradas</h3>
                {{-- CORREGIDO: La función se llama 'create' en el componente --}}
                <button wire:click="crear()" class="bg-red-700 hover:bg-red-800 text-white px-4 py-3 rounded-lg font-semibold transition">
                    Crear Nueva Unidad
                </button>
            </div>
            
            {{-- Buscador --}}
            <div class="mb-4">
                 <input wire:model.live.debounce.300ms="searchTerm" type="text" placeholder="Buscar por nombre de unidad..." class="w-full lg:w-1/3 p-2 border border-gray-300 rounded-lg">
            </div>

            {{-- Tabla de Unidades de Trabajo --}}
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
                                    {{-- CORREGIDO: La función se llama 'edit' en el componente --}}
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

            {{-- Paginación --}}
            @if ($unidades->hasPages())
                <div class="mt-6">
                    {{ $unidades->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Inclusión del Modal (se muestra condicionalmente si $isOpen es true) --}}
    @if ($isOpen)
        @include('livewire.unidades-trabajo-modal')
    @endif
</div>