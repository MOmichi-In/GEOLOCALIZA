<x-guest-layout>

    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-white to-whitepy-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-9">
         
            <div class="flex justify-center mt-6">
                <div class="bg-red-700 p-4 rounded-full shadow-lg">
                    <img src="{{ asset('images/blanco.svg') }}" alt="Logo RIB Logísticas" class="h-16 w-auto">
                </div>
            </div>

            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    {{ __('Iniciar Sesión') }}
                </h2>

            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="group">
                    <x-input-label for="email" :value="__('Correo')"
                        class="text-sm font-medium text-gray-700 mb-2 block" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-700 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            autofocus autocomplete="username" placeholder="{{ __('admin@ejemplo.com') }}"
                            class="block w-full pl-10 pr-3 py-3 bg-white/70 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-xl shadow-sm transition-all duration-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:bg-white hover:bg-white/90" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="group">
                    <x-input-label for="password" :value="__('Contraseña')"
                        class="text-sm font-medium text-gray-700 mb-2 block" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-700 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            placeholder="{{ __('Ingresa tu contraseña') }}"
                            class="block w-full pl-10 pr-3 py-3 bg-white/70 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-xl shadow-sm transition-all duration-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:bg-white hover:bg-white/90" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                            class="h-4 w-4 text-red-700 focus:ring-red-700 border-gray-300 rounded transition-colors" />
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}"
                                class="font-medium text-red-700  transition-colors">
                                {{ __('Olvidaste tu contraseña?') }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Button -->
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-red-700 to-red-700 hover:from-red-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-red-700 group-hover:text-red-700 transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </span>
                        {{ __('Sign in') }}
                    </button>
                </div>


            </form>
        </div>
    </div>
    </div>
</x-guest-layout>
