<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            <livewire:layout.navigation />

            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-grow">
                {{ $slot }}
            </main>
       
            <footer class="bg-white shadow mt-auto">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                    ArcaIF &copy; {{ date('Y') }} -
                    @php
                        try {
                            $gitVersion = trim(shell_exec('git describe --tags --abbrev=0'));
                            if ($gitVersion) {
                                echo 'Versão: ' . htmlspecialchars($gitVersion);
                            } else {
                                echo 'Versão: dev';
                            }
                        } catch (\Exception $e) {
                            echo 'Versão: dev';
                        }
                    @endphp
                </div>
            </footer>
        </div>
        
        {{-- Componente Toast para notificações --}}
        <x-toast />

        @livewireScripts
    </body>
</html>