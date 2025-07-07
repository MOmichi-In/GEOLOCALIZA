<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                autocomplete="username"
                class="block mt-4 w-full bg-white text-black border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <input id="password" name="password" type="password" required autocomplete="current-password"
                class="block mt-1 w-full bg-white text-black border border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded bg-gray-100 border-white text-red-600 shadow-sm focus:ring-red-500  focus:ring-offset-gray-100"
                    name="remember">
                <span class="ms-2 text-sm text-black ">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-00   hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 focus:ring-offset-gray-100"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
