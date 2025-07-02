<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- ... outros meta e links ... --}}

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    {{-- NOVO SCRIPT DO CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Scripts do Vite e Livewire --}}
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
   
        
    </div>
     
  

    {{-- NOVO RODAPÉ --}}
    <footer class="bg-white shadow mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            ArcaIF &copy; {{ date('Y') }} -
            @php
                try {
                    // Executa o comando git para pegar a última tag
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
      @livewireScripts
</body>

</html>

</div>
</body>

</html>
