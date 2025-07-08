<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Gestión de Unidades de Trabajo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <button wire:click="create()" class="bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mb-4">
                        Crear Nueva Unidad
                    </button>

                    @if($isOpen)
                        @include('livewire.unidades-trabajo-modal') {{-- Crearemos este archivo --}}
                    @endif

                    <input wire:model.live.debounce.300ms="searchTerm" type="text" placeholder="Buscar unidades..." class="mb-4 p-2 border rounded w-full">

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($unidades as $unidad)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $unidad->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="edit({{ $unidad->id }})" class="text-gray-600 hover:text-gray-900">Editar</button>
                                        <button wire:click="delete({{ $unidad->id }})" wire:confirm="¿Estás seguro de eliminar esta unidad?" class="text-red-600 hover:text-red-900 ml-2">Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        No hay unidades de trabajo registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $unidades->links() }} {{-- Paginación --}}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <!-- ... mensajes flash ... -->
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-gray rounded">
        <div class="flex justify-between items-center mb-9">
            <h3 class="text-2xl font-bold text-gray-800">Unidades de Trabajo Registradas</h3>
            <button wire:click="crear()" class="bg-red-700 hover:bg-red-800 text-white px-4 py-3 rounded transition">
                Crear Nueva Unidad
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Unidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor a Cargo</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Operadores Asignados</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($unidades as $unidad)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $unidad->nombre }}</td>
                            <td class="px-6 py-3">{{ $unidad->supervisor->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-center font-bold">{{ $unidad->operadores_count }}</td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex justify-center">
                                    <button wire:click="editar({{ $unidad->id }})" class="bg-gray-400 hover:bg-gray-600 text-white px-3 py-1 rounded">
                                        Gestionar
                                    </button>
                                </div>
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
        
        <div class="mt-4">{{ $unidades->links() }}</div>
    </div>

    <!-- Incluimos el modal -->
    @if ($isOpen)
        @include('livewire.unidades-trabajo-modal')
    @endif
</div>