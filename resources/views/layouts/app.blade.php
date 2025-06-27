<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ADICIONE ESTA LINHA -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            {{--
    ARQUIVO: resources/views/layouts/app.blade.php
    INSTRUÇÃO: Cole este código antes do fechamento da tag </body> no final do arquivo.
--}}

        {{-- ... O restante do seu arquivo app.blade.php ... --}}
        </main>
    </div>

    @livewireScripts

    {{-- NOVO RODAPÉ --}}
    <footer class="bg-white shadow mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            ArcaIF &copy; {{ date('Y') }} -
            @php
                try {
                    // Executa o comando git para pegar a última tag
                    $gitVersion = trim(shell_exec('git describe --tags --abbrev=0'));
                    if ($gitVersion) {
                        echo "Versão: " . htmlspecialchars($gitVersion);
                    } else {
                        echo "Versão: dev";
                    }
                } catch (\Exception $e) {
                    echo "Versão: dev";
                }
            @endphp
        </div>
    </footer>
</body>
</html>

        </div>
    </body>
</html>
