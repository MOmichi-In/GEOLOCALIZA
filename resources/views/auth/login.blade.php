<x-guest-layout>
    
        <div class="w-full max-w-5xl bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col md:flex-row">

            <!-- Lado Izquierdo: Logo o Imagen -->
            <div class="md:w-1/2 bg-red-700 text-white flex flex-col justify-center items-center p-10 space-y-4">
                <img src="{{ asset('images/blanco.svg') }}" alt="Logo RIB Logísticas" class="h-25 w-auto">
            
            </div>

            <!-- Lado Derecho: Formulario -->
            <div class="md:w-1/2 w-full p-4 sm:p-10 space-y-6">
                <div class="text-center">
                    <h3 class="text-3xl font-semibold text-gray-800">{{ __('Iniciar Sesión') }}</h3>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input id="email" name="email" type="email" required autofocus
                            value="{{ old('email') }}" placeholder="correo@ejemplo.com"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input id="password" name="password" type="password" required placeholder="••••••••"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="h-4 w-4 text-red-700 focus:ring-red-500 border-gray-300 rounded">
                            <span class="ml-2 text-gray-700">Recuérdame</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-red-700 hover:text-red-600 font-medium">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Botón -->
                    <div>
                        <button type="submit"
                            class="w-full py-2 px-4 bg-red-700 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition">
                            Iniciar sesión
                        </button>
                    </div>
                </form>

            </div>
        </div>
</x-guest-layout>
