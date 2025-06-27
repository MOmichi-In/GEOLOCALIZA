{{-- resources/views/livewire/unidades-trabajo-modal.blade.php --}}
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Fondo del modal --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal()"></div>

        {{-- Contenedor para centrar el contenido del modal --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

        {{-- Contenido del Modal --}}
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form wire:submit.prevent="store">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $unidad_id ? 'Editar Unidad de Trabajo' : 'Crear Nueva Unidad de Trabajo' }}
                            </h3>
                            <div class="mt-2">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Unidad</label>
                                <input type="text" wire:model.lazy="nombre" id="nombre" name="nombre"
                                       class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('nombre') border-red-500 @enderror"
                                       placeholder="Ej: Barranquilla Centro">
                                @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ $unidad_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button type="button" wire:click="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>