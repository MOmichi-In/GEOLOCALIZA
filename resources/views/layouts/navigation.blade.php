<!-- Sidebar Component - Reemplaza solo tu nav actual -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<div x-data="{ sidebarOpen: false }" class="relative">
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-red-700 to-red-800 shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0">

        <!-- Logo Section -->
        <div class="flex justify-center items-center p-4 sm:p-8">
            <x-application-logo class="w-auto h-12 sm:h-16" />
        </div>
        {{-- // --}}
        <!-- Navigation Menu -->
        <nav class="mt-8 px-4">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group
        {{ request()->routeIs('dashboard')
            ? 'bg-red-900 text-white shadow-inner border-r-4 border-red-300'
            : 'text-red-100 hover:text-white hover:bg-red-600' }}">

                    <span
                        class="material-symbols-outlined text-xl mr-3 
        {{ request()->routeIs('dashboard') ? 'text-red-300' : 'text-red-200 group-hover:text-white' }}">
                        home
                    </span>

                    <span>{{ __('Inicio') }}</span>
                </a>


                @if (Auth::user()->rol === 'Coordinador_Administrativo')
                    <!-- Unidades de Trabajo -->
                    <a href="{{ route('unidades.index') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group
                              {{ request()->routeIs('unidades.index')
                                  ? 'bg-red-900 text-white shadow-inner border-r-4 border-red-300'
                                  : 'text-red-100 hover:text-white hover:bg-red-600' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('unidades.index') ? 'text-red-300' : 'text-red-200 group-hover:text-white' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span>{{ __('Unidades de Trabajo') }}</span>
                    </a>
                @endif

                @if (Auth::user()->rol === \App\Models\User::ROLE_COORDINADOR_ADMINISTRATIVO)
                    <!-- Asignar Tareas -->
                    <a href="{{ route('tasks.assign') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group
          {{ request()->routeIs('tasks.assign')
              ? 'bg-red-900 text-white shadow-inner border-r-4 border-red-300'
              : 'text-red-100 hover:text-white hover:bg-red-600' }}">

                        <span
                            class="material-symbols-outlined text-xl mr-3 
        {{ request()->routeIs('tasks.assign') ? 'text-red-300' : 'text-red-200 group-hover:text-white' }}">
                            assignment_add
                        </span>

                        <span>{{ __('Asignar Tareas') }}</span>
                    </a>

                    <a href="{{ route('supervisor.dashboard') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group
        {{ request()->routeIs('supervisor.dashboard')
            ? 'bg-red-900 text-white shadow-inner border-r-4 border-red-300'
            : 'text-red-100 hover:text-white hover:bg-red-600' }}">

                        <span
                            class="material-symbols-outlined text-xl mr-3 
        {{ request()->routeIs('supervisor.dashboard') ? 'text-red-300' : 'text-red-200 group-hover:text-white' }}">
                            supervisor_account
                        </span>

                        <span>{{ __('Supervisor ') }}</span>
                    </a>
                @endif

                <!-- Usuarios -->
                <a href="{{ route('users') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 group
        {{ request()->routeIs('usuarios')
            ? 'bg-red-900 text-white shadow-inner border-r-4 border-red-300'
            : 'text-red-100 hover:text-white hover:bg-red-600' }}">

                    <span
                        class="material-symbols-outlined text-xl mr-3 
        {{ request()->routeIs('users') ? 'text-red-300' : 'text-red-200 group-hover:text-white' }}">
                        groups
                    </span>

                    <span>{{ __('Usuarios') }}</span>
                </a>

            </div>
        </nav>

        <!-- User Profile Section -->
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-red-900 border-t border-red-800">
            <div x-data="{ profileOpen: false }" class="relative">
                <button @click="profileOpen = !profileOpen"
                    class="flex items-center w-full px-3 py-2 text-sm font-medium text-white rounded-lg hover:bg-red-800 transition-colors duration-200">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center mr-3">
                        <span class="text-red-700 font-semibold text-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1 text-left">
                        <div class="text-sm font-medium truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-red-200 truncate">{{ Auth::user()->email }}</div>
                    </div>
                    <svg class="w-4 h-4 ml-2 transition-transform duration-200" :class="{ 'rotate-180': profileOpen }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div x-show="profileOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Perfil') }}
                        </a>

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors duration-200 text-left">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                {{ __('Cerrar Sesión') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Header (solo visible en mobile) -->
    <header class="bg-white shadow-sm border-b border-gray-200 lg:hidden fixed top-0 left-0 right-0 z-40">
        <div class="px-4 sm:px-6">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500 transition-colors duration-200">
                        <span class="sr-only">Abrir menú</span>
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h1 class="ml-4 text-lg font-semibold text-gray-900">Sistema</h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 lg:hidden">
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75"
            @click="sidebarOpen = false"></div>
    </div>
</div>

<!-- CSS CORREGIDO - Agrega esto a tu layout principal -->
<style>
    /* Estilos para el layout principal */
    .app-layout {
        min-height: 100vh;
        display: flex;
    }

    /* Contenedor principal del contenido */
    .main-content {
        flex: 1;
        padding: 0;
        margin-left: 0;
        min-height: 100vh;
    }

    /* En desktop, aplicar margen izquierdo para el sidebar */
    @media (min-width: 1024px) {
        .main-content {
            margin-left: 16rem;
            /* 256px - ancho del sidebar */
        }
    }

    /* En mobile, aplicar padding top para el header */
    @media (max-width: 1023px) {
        .main-content {
            padding-top: 4rem;
            /* 64px - altura del header móvil */
        }
    }

    /* Asegurar que el body no tenga padding por defecto */
    body {
        margin: 0;
        padding: 0;
    }

    /* Estilos para el contenido interno de las páginas */
    .page-content {
        padding: 1.5rem;
        /* 24px */
    }

    @media (min-width: 640px) {
        .page-content {
            padding: 2rem;
            /* 32px */
        }
    }

    @media (min-width: 1024px) {
        .page-content {
            padding: 2.5rem;
            /* 40px */
        }
    }
</style>
