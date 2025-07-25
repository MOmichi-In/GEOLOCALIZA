<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Panel de importación') }}
        </h2>
    </x-slot>


<div class="py-0">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-800">

                <div class="p-6 bg-white rounded-lg shadow-md mb-8">
                    <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">Cargar Archivo Excel</h2>

                    
                    <form wire:submit.prevent="import" class="space-y-4">
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar archivo Excel:</label>
                            <input type="file" id="file" wire:model="file"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                                aria-describedby="file_input_help">
                            <p class="mt-1 text-sm text-gray-500" id="file_input_help">Solo archivos XLSX, XLS o CSV (Máx 10MB).</p>

                            @error('file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-center mt-6">
                            <button type="submit"
                                class="px-6 py-2 text-white bg-red-700 rounded-md hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50"
                                wire:loading.attr="disabled"
                                wire:target="file, import">
                                <span wire:loading.remove wire:target="import">Guardar Archivo</span>
                            </button>
                        </div>
                    </form>

                    @if ($message)
                        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
                             class="mt-6 p-4 rounded-md relative pr-10
                                    {{ $messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}"
                             role="alert">

                            <div class="flex items-center">
                                @if ($messageType === 'success')
                                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @else
                                    <svg class="h-6 w-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                                <div class="font-semibold">
                                    @if ($messageType === 'success')
                                        ¡Operación Exitosa!
                                    @else
                                        ¡Error en la Importación!
                                    @endif
                                </div>
                            </div>
                            <p class="mt-2 text-sm leading-tight">
                                {!! $message !!}
                            </p>

                            <button @click="show = false" type="button" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                                <span class="sr-only">Cerrar alerta</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endif

                    <div x-data="{ isUploading: false, progress: 0 }"
                         x-on:livewire-upload-start="isUploading = true"
                         x-on:livewire-upload-finish="isUploading = false"
                         x-on:livewire-upload-error="isUploading = false"
                         x-on:livewire-upload-progress="progress = $event.detail.progress"
                         class="mt-4">

                        <div x-show="isUploading">
                            <progress max="100" x-bind:value="progress"
                                      class="w-full h-2 rounded-full overflow-hidden bg-gray-200">
                                <div class="h-full bg-red-600 transition-all duration-300" :style="`width: ${progress}%`"></div>
                            </progress>
                            <p class="text-xs text-gray-500 mt-1">Subiendo archivo... <span x-text="progress"></span>%</p>
                        </div>
                    </div>
                </div> 

                {{-- Sección de Filtros y Tabla --}}
                <div class="mt-8 p-6 bg-white rounded-lg shadow-md">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="filterRutaStatic" class="block text-sm font-medium text-gray-700">Ruta:</label>
                            <input type="text" id="filterRutaStatic"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                placeholder="Filtrar por ruta">
                        </div>
                        <div>
                            <label for="filterDireccionStatic" class="block text-sm font-medium text-gray-700">Dirección:</label>
                            <input type="text" id="filterDireccionStatic"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                placeholder="Filtrar por dirección">
                        </div>
                        <div>
                            <label for="filterPropietarioStatic" class="block text-sm font-medium text-gray-700">Propietario:</label>
                            <input type="text" id="filterPropietarioStatic"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                placeholder="Filtrar por propietario">
                        </div>
                    </div>

                    {{-- Botón de Filtro --}}
                    <div class="flex justify-center mb-6">
                        <button type="button" class="px-6 py-2 text-white bg-red-700 rounded-md hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Organizar
                        </button>
                    </div>

                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Registros Importados </h3>

                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3 px-6">ID</th>
                                    <th scope="col" class="py-3 px-6">Ruta</th>
                                    <th scope="col" class="py-3 px-6">Dirección</th>
                                    <th scope="col" class="py-3 px-6">Nombre del Propietario</th>
                                    <th scope="col" class="py-3 px-6">Fecha de Carga</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Filas de ejemplo  --}}
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6">101</td>
                                    <td class="py-4 px-6">Ruta A-1</td>
                                    <td class="py-4 px-6">Calle Falsa 123</td>
                                    <td class="py-4 px-6">Juan Pérez</td>
                                    <td class="py-4 px-6">24/07/2025 10:00</td>
                                </tr>
                               
                            </tbody>
                        </table>
                    </div>

                </div>
            </div> 
        </div> 
    </div>
</div> 