<div>
    {{-- El título de la página que se mostrará en la plantilla principal --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Gestión de Tipos de Actividad') }}
        </h2>
    </x-slot>

    {{-- Contenedor principal --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensaje de éxito (flash message) --}}
            @if (session()->has('message'))
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('message') }}
                </div>
            @endif

            {{-- Sección del contenido (tabla, botones) --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Actividades Registradas</h3>
                    {{-- Botón que llama a la función mostrarModal() en el componente --}}
                    <button wire:click="mostrarModal"
                        class="bg-red-700 hover:bg-red-800 text-white px-4 py-3 rounded-lg font-semibold transition">
                        Crear Nueva Actividad
                    </button>
                </div>

                {{-- Campo de Búsqueda --}}
                <div class="mb-4">
                    <input wire:model.live.debounce.300ms="searchTerm" type="text" placeholder="Buscar actividad..."
                        class="w-full lg:w-1/3 p-2 border border-gray-300 rounded-lg">
                </div>

                {{-- Tabla de Actividades --}}
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Bucle para mostrar cada actividad --}}
                            @forelse ($actividades as $actividad)
                                {{-- CAMBIO CRUCIAL AQUÍ: Añadir wire:key --}}
                                <tr wire:key="activity-{{ $actividad->id }}" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $actividad->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="editar({{ $actividad->id }})"
                                                class="bg-gray-400 hover:bg-gray-600 text-white px-3 py-1 rounded">Editar</button>
                                            <button wire:click="eliminar({{ $actividad->id }})"
                                                wire:confirm="¿Estás seguro de eliminar esta actividad?"
                                                class="bg-red-800 hover:bg-red-700 text-white px-3 py-1 rounded">Eliminar</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-gray-500 py-4">No hay actividades
                                        registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if ($actividades->hasPages())
                    <div class="mt-6">
                        {{ $actividades->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL --}}
        {{-- Se muestra condicionalmente si la variable $modalVisible es true --}}
        @if ($modalVisible)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
                    <button wire:click="ocultarModal"
                        class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl font-bold">×</button>
                    <h2 class="text-xl font-bold mb-4 text-center">
                        {{ $editando ? 'Editar Actividad' : 'Crear Actividad' }}</h2>

                    {{-- Formulario que llama a 'guardar()' al enviarse --}}
                    <form wire:submit.prevent="guardar">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Nombre de la Actividad</label>
                            <input type="text" wire:model="nombre"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500">
                            @error('nombre')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-3 pt-6">
                            <button type="button" wire:click="ocultarModal"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">Cancelar</button>
                            <button type="submit"
                                class="bg-red-800 hover:bg-red-700 text-white px-5 py-2 rounded">{{ $editando ? 'Actualizar' : 'Guardar' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
