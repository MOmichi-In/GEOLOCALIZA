<section>
    <header>
        <h2 class="text-lg font-medium text-black">
            {{ __('Actualizar contraseña') }}
        </h2>

        <p class="mt-1 text-sm text-gray-900">
            {{ __('Asegúrese de que su cuenta utilice una contraseña larga y aleatoria para mantener su seguridad.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Actual Contraseña')" />
            <input id="update_password_current_password" name="current_password" type="password"
                autocomplete="current-password"
                class="mt-1 block w-full bg-white text-black border border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="update_password_password" :value="__('Nueva contraseña')" />
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                class="mt-1 block w-full bg-white text-black border border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirma contraseña')" />
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password"
                class="mt-1 block w-full bg-white text-black border border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
