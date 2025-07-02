{{-- ARQUIVO: resources/views/livewire/dashboard.blade.php --}}
<div>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Grid de Estatísticas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ url('/animais') }}" class="transform hover:scale-105 transition-transform duration-300">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <i class="fas fa-paw fa-2x text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Animais Ativos</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalAnimaisAtivos }}</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ url('/especies') }}" class="transform hover:scale-105 transition-transform duration-300">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="fas fa-book-open fa-2x text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Espécies Cadastradas</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalEspecies }}</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ url('/racas') }}" class="transform hover:scale-105 transition-transform duration-300">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <i class="fas fa-dna fa-2x text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Raças Cadastradas</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalRacas }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- ... o resto do seu dashboard (gráfico e resumo) ... --}}
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-5 gap-8">
                
                {{-- Card do Gráfico --}}
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                         <h3 class="text-lg font-medium leading-6 text-gray-900">Distribuição por Espécie</h3>
                         <div
                            wire:ignore
                            x-data="{
                                chart: null,
                                chartData: {{ json_encode($chartData) }},
                                initChart() {
                                    if (this.chart) { this.chart.destroy(); }
                                    const ctx = this.$refs.canvas.getContext('2d');
                                    this.chart = new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: this.chartData.labels,
                                            datasets: [{
                                                data: this.chartData.data,
                                                backgroundColor: ['#4f46e5', '#22c55e', '#eab308', '#ef4444', '#3b82f6'],
                                                hoverOffset: 4
                                            }]
                                        },
                                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                                    });
                                }
                            }"
                            x-init="initChart(); $wire.on('update-chart', ({ data }) => { chartData = data; initChart(); });"
                            class="mt-4 h-64"
                         >
                            <canvas x-ref="canvas"></canvas>
                         </div>
                    </div>
                </div>

                {{-- Card de Resumo por Espécie --}}
                <div class="lg:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Resumo do Rebanho</h3>
                        <div class="mt-4 border-t border-gray-200">
                            <dl class="divide-y divide-gray-200">
                                @forelse ($animaisPorEspecie as $especie)
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">{{ $especie->nome }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-bold">{{ $especie->animais_count }} {{ \Illuminate\Support\Str::plural('animal', $especie->animais_count) }}</dd>
                                    </div>
                                @empty
                                    <div class="py-4 text-sm text-gray-500 text-center">
                                        Ainda não há animais cadastrados para exibir um resumo.
                                    </div>
                                @endforelse
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>