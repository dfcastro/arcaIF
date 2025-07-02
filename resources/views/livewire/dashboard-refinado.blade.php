<div>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Painel de Ações Imediatas: Agenda Sanitária --}}
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Agenda Sanitária do Dia e Atrasos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Card: Atrasados --}}
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h4 class="font-bold text-red-700 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Atrasados ({{ $eventosAtrasados->count() }})
                        </h4>
                        <div class="mt-3 space-y-2 max-h-40 overflow-y-auto">
                            @forelse($eventosAtrasados as $evento)
                                <div class="bg-white p-2 rounded-md shadow-sm text-sm">
                                    <p class="font-semibold">{{ $evento->protocoloEvento->nome_evento }}</p>
                                    <a href="{{ route('animais.show', $evento->animal) }}" class="text-xs text-if-green-600 hover:underline">Animal: {{ $evento->animal->identificacao }}</a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Nenhum evento atrasado.</p>
                            @endforelse
                        </div>
                    </div>
                    {{-- Card: Para Hoje --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-bold text-blue-700 flex items-center">
                            <i class="fas fa-calendar-day mr-2"></i> Para Hoje ({{ $eventosHoje->count() }})
                        </h4>
                        <div class="mt-3 space-y-2 max-h-40 overflow-y-auto">
                            @forelse($eventosHoje as $evento)
                                <div class="bg-white p-2 rounded-md shadow-sm text-sm">
                                    <p class="font-semibold">{{ $evento->protocoloEvento->nome_evento }}</p>
                                    <a href="{{ route('animais.show', $evento->animal) }}" class="text-xs text-if-green-600 hover:underline">Animal: {{ $evento->animal->identificacao }}</a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Nenhum evento para hoje.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cartões de Resumo Financeiro e de Rebanho --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="p-4 bg-white rounded-lg shadow text-center">
                    <p class="text-sm font-medium text-gray-500">Custo Diário com Ração</p>
                    <p class="mt-1 text-3xl font-bold text-if-green-600">R$ {{ number_format($custoDiarioRacao, 2, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow text-center">
                    <p class="text-sm font-medium text-gray-500">Consumo Diário Previsto</p>
                    <p class="mt-1 text-3xl font-bold text-if-green-600">{{ number_format($consumoDiarioRacao, 2, ',', '.') }} kg</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow text-center">
                    <p class="text-sm font-medium text-gray-500">Animais Ativos</p>
                    <p class="mt-1 text-3xl font-bold text-if-green-600">{{ $totalAnimaisAtivos }}</p>
                </div>
                <div class="p-4 bg-white rounded-lg shadow text-center">
                    <p class="text-sm font-medium text-gray-500">Total de Espécies</p>
                    <p class="mt-1 text-3xl font-bold text-if-green-600">{{ $totalEspecies }}</p>
                </div>
            </div>

            {{-- Seção de Gráficos --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Gráfico de Composição por Categoria --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900"
                        x-data="{
                            chart: null,
                            initChart() {
                                if (this.chart) { this.chart.destroy(); }
                                const ctx = this.$refs.canvas.getContext('2d');
                                this.chart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: {{ json_encode($chartDataCategorias['labels']) }},
                                        datasets: [{
                                            label: 'Nº de Animais',
                                            data: {{ json_encode($chartDataCategorias['data']) }},
                                            backgroundColor: 'rgba(22, 163, 74, 0.5)',
                                            borderColor: 'rgba(22, 163, 74, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                                        plugins: { legend: { display: false } }
                                    }
                                });
                            }
                        }"
                        x-init="initChart()"
                    >
                        <h3 class="text-lg font-medium leading-6">Composição do Rebanho por Categoria</h3>
                        <div class="mt-4 h-80"><canvas x-ref="canvas"></canvas></div>
                    </div>
                </div>

                {{-- Gráfico de Composição por Espécie (Pizza) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900"
                        x-data="{
                            chart: null,
                            initPieChart() {
                                if (this.chart) { this.chart.destroy(); }
                                const ctx = this.$refs.pieCanvas.getContext('2d');
                                this.chart = new Chart(ctx, {
                                    type: 'pie',
                                    data: {
                                        labels: {{ json_encode($chartDataEspecies['labels']) }},
                                        datasets: [{
                                            label: 'Nº de Animais',
                                            data: {{ json_encode($chartDataEspecies['data']) }},
                                            backgroundColor: [
                                                'rgba(34, 197, 94, 0.7)',
                                                'rgba(59, 130, 246, 0.7)',
                                                'rgba(249, 115, 22, 0.7)',
                                                'rgba(139, 92, 246, 0.7)',
                                                'rgba(239, 68, 68, 0.7)',
                                            ],
                                            borderColor: '#fff',
                                            borderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'bottom',
                                            }
                                        }
                                    }
                                });
                            }
                        }"
                        x-init="initPieChart()"
                    >
                        <h3 class="text-lg font-medium leading-6">Composição do Rebanho por Espécie</h3>
                        <div class="mt-4 h-80"><canvas x-ref="pieCanvas"></canvas></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>