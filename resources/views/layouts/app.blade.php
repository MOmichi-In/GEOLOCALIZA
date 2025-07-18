<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RIBCONTROL') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="app-layout">
        <!-- Incluir el sidebar -->
        @include('layouts.navigation')

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="page-content">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="mb-6">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>

    <!-- CSS Styles -->
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
            background-color: #f9fafb;
            /* bg-gray-50 */
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

    @livewireScripts
    @stack('scripts')

</body>

</html>
