<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Asignación de Tareas') }}
        </h2>
    </x-slot>

    <!-- Mensaje de éxito -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Errores -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-700 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">Se encontraron los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario -->
    <div class="max-w-4xl mx-auto bg-gray-50 rounded-lg p-9 mb-8">
        <form wire:submit.prevent="guardar" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Nombre</label>
                    <input 
                        type="text" 
                        wire:model="name" 
                        placeholder="Ingresa el nombre completo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700 focus:border-red-700 transition-colors"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <input 
                        type="email" 
                        wire:model="email" 
                        placeholder="ejemplo@correo.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-700  focus:border-red-700 transition-colors"
                    >
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Contraseña</label>
                    <input 
                        type="password" 
                        wire:model="password" 
                        placeholder="Ingresa una contraseña segura"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-colors"
                    >
                </div>

                <!-- Rol -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Rol</label>
                    <select 
                        wire:model="rol"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-colors bg-white"
                    >
                        <option value="">Selecciona un rol</option>
                        <option value="Analista">Analista</option>
                        <option value="Operador_Logistico">Operador Logístico</option>
                        <option value="Coordinador_Administrativo">Coordinador Administrativo</option>
                        <option value="Supervisor">Supervisor</option>
                    </select>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-3 pt-7">
                <button 
                    type="submit"
                    class="flex-1 bg-red-800 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:ring-2 focus:ring-red-800 focus:ring-opacity-50"
                >
                    {{ $editando ? 'Actualizar Usuario' : 'Guardar Usuario' }}
                </button>

                @if ($editando)
                    <button 
                        type="button" 
                        wire:click="limpiarCampos"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
                    >
                        Cancelar
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Separador -->
    <div class="border-t border-gray-200 my-8"></div>

    <!-- Tabla de usuarios -->
    <div class="bg-white">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Usuarios Registrados</h3>
        
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rol
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $usuario->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $usuario->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-1 py-2 text-s font-semibold rounded-full 
                                    @if($usuario->rol == 'Supervisor') bg-purple-100 text-purple-800
                                    @elseif($usuario->rol == 'Coordinador_Administrativo') bg-blue-100 text-blue-800
                                    @elseif($usuario->rol == 'Analista') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ str_replace('_', ' ', $usuario->rol ?? 'Sin rol') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <button 
                                        wire:click="editar({{ $usuario->id }})" 
                                        type="button"
                                        class="bg-green-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50"
                                    >
                                        Editar
                                    </button>
                                    <button 
                                        wire:click="eliminar({{ $usuario->id }})" 
                                        type="button"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                                        onclick="return confirm('¿Estás seguro de eliminar este usuario?')"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    <p class="text-sm font-medium">No hay usuarios registrados</p>
                                    <p class="text-sm text-gray-400">Comienza creando tu primer usuario</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>