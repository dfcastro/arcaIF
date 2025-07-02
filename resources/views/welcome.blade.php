<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="bg-gray-100 text-gray-800">
            <div class="relative min-h-screen flex flex-col items-center justify-center">
                
                <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
                    
                    {{-- Logo Institucional --}}
                    <div class="flex justify-center mb-4">
                        {{-- Certifique-se que o caminho e o nome do ficheiro estão corretos --}}
                        <img src="{{ asset('images/logo-ifnmg.png') }}" alt="Logo IFNMG - Campus Almenara" class="h-24">
                    </div>

                    {{-- Logo do Sistema --}}
                    <div class="flex justify-center mb-8">
                        <x-application-logo class="h-20" />
                    </div>

                    <h1 class="text-center text-2xl font-bold text-gray-700">
                        Sistema de Gestão de Rebanhos
                    </h1>
                    <p class="text-center text-sm text-gray-500 mt-1">
                        Por favor, faça o login para continuar.
                    </p>

                    {{-- Botão de Acesso --}}
                    @if (Route::has('login'))
                        <div class="mt-8">
                            <a
                                href="{{ route('login') }}"
                                class="w-full flex justify-center items-center px-6 py-3 bg-if-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-if-green-700 transition-colors"
                            >
                                Aceder ao Sistema
                            </a>
                        </div>
                    @endif
                </div>

                <footer class="py-8 text-center text-sm text-gray-500 w-full absolute bottom-0">
                    IFNMG - Campus Almenara &copy; {{ date('Y') }}
                </footer>

            </div>
        </div>
    </body>
</html>