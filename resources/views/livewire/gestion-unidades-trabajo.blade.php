<div>
    {{-- layouts.app generalmente tiene un slot para el header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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

                    <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
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
                                        <button wire:click="edit({{ $unidad->id }})" class="text-indigo-600 hover:text-indigo-900">Editar</button>
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
    </div>
</div>