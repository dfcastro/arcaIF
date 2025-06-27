<div>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Seção de Filtros --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Gerador de Relatórios</h3>
                        <div class="mt-4 p-4 border border-gray-200 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Filtro: Tipo de Relatório --}}
                                <div>
                                    <label for="tipoRelatorio" class="block text-sm font-medium text-gray-700">Tipo de Relatório</label>
                                    <select wire:model.live="tipoRelatorio" id="tipoRelatorio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Selecione...</option>
                                        <option value="animais_por_lote">Animais por Lote</option>
                                        {{-- Outros tipos de relatório podem ser adicionados aqui --}}
                                    </select>
                                </div>

                                {{-- Filtro: Lote (só aparece se o relatório for 'animais_por_lote') --}}
                                @if($tipoRelatorio === 'animais_por_lote')
                                <div>
                                    <label for="loteId" class="block text-sm font-medium text-gray-700">Selecione o Lote</label>
                                    <select wire:model="loteId" id="loteId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Selecione...</option>
                                        @foreach($lotes as $lote)
                                            <option value="{{ $lote->id }}">{{ $lote->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                {{-- Botão Gerar Relatório --}}
                                <div class="self-end">
                                    <button wire:click="gerarRelatorio" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                        Gerar Relatório
                                    </button>
                                </div>
                            </div>
                            @error('loteId') <span class="text-red-500 text-xs mt-1">É necessário selecionar um lote.</span> @enderror
                        </div>
                    </div>

                    {{-- Seção de Resultados --}}
                    @if(!empty($dadosRelatorio))
                        <div id="area-impressao">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-xl font-bold">Relatório de Animais do Lote: {{ $loteSelecionado->nome }}</h3>
                                    <p class="text-sm text-gray-500">Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
                                </div>
                                <button onclick="imprimirRelatorio()" class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                    <i class="fas fa-print mr-2"></i> Imprimir
                                </button>
                            </div>
                            <div class="border-t border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identificação</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Espécie</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raça</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nascimento</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($dadosRelatorio as $animal)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $animal->identificacao }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->especie->nome }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->raca->nome ?? 'N/D' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($animal->data_nascimento)->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <script>
                            function imprimirRelatorio() {
                                var conteudo = document.getElementById('area-impressao').innerHTML;
                                var telaImpressao = window.open('', '_blank');
                                telaImpressao.document.write('<html><head><title>Imprimir Relatório</title>');
                                // Adiciona o Tailwind CSS para a impressão
                                telaImpressao.document.write('<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">');
                                telaImpressao.document.write('<style>body{font-family: sans-serif;}</style>');
                                telaImpressao.document.write('</head><body>');
                                telaImpressao.document.write(conteudo);
                                telaImpressao.document.write('</body></html>');
                                telaImpressao.document.close();
                                telaImpressao.focus();
                                setTimeout(function(){telaImpressao.print();},500); // Pequeno delay para garantir o carregamento do CSS
                            }
                        </script>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
