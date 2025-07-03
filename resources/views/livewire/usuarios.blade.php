<div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Gestión de usuarios') }}
        </h2>
    </x-slot>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-gray rounded">
        <div class="flex justify-between items-center mb-9">
            <h3 class="text-2xl font-bold text-gray-800">Usuarios Registrados</h3>
            <button wire:click="mostrarModal" class="bg-red-700 hover:bg-red-800 text-white px-4 py-3 rounded transition">
                Crear Usuario
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $usuario->name }}</td>
                            <td class="px-6 py-3">{{ $usuario->email }}</td>
                            <td class="px-6 py-3">{{ str_replace('_', ' ', $usuario->rol ?? 'Sin rol') }}</td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <button wire:click="editar({{ $usuario->id }})" class="bg-gray-400 hover:bg-gray-600 text-white px-3 py-1 rounded">
                                        Editar
                                    </button>
                                    <button wire:click="eliminar({{ $usuario->id }})" onclick="return confirm('¿Estás seguro?')" class="bg-red-800 hover:bg-red-700 text-white px-3 py-1 rounded">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($modalVisible)
    <!-- MODAL -->
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-6 relative">
            <button wire:click="ocultarModal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl font-bold">&times;</button>

            <h2 class="text-xl font-bold mb-4 text-center">
                {{ $editando ? 'Editar Usuario' : 'Crear Usuario' }}
            </h2>

            <form wire:submit.prevent="guardar" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Nombre</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2 border rounded-lg">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Correo</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-2 border rounded-lg">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Contraseña</label>
                        <input type="password" wire:model="password" class="w-full px-4 py-2 border rounded-lg">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Rol</label>
                        <select wire:model="rol" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">Selecciona un rol</option>
                            <option value="Analista">Analista</option>
                            <option value="Operador_Logistico">Operador Logístico</option>
                            <option value="Coordinador_Administrativo">Coordinador Administrativo</option>
                            <option value="Supervisor">Supervisor</option>
                        </select>
                        @error('rol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit" class="bg-red-800 hover:bg-red-700 text-white px-5 py-2 rounded">
                        {{ $editando ? 'Actualizar Usuario' : 'Guardar Usuario' }}
                    </button>
                    <button type="button" wire:click="ocultarModal" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
