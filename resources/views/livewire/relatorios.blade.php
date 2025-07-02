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
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                {{-- Filtro: Tipo de Relatório --}}
                                <div class="col-span-1 md:col-span-1">
                                    <label for="tipoRelatorio" class="block text-sm font-medium text-gray-700">Tipo de Relatório</label>
                                    <select wire:model.live="tipoRelatorio" id="tipoRelatorio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Selecione...</option>
                                        <option value="animais_por_lote">Animais por Lote</option>
                                        <option value="historico_pesagem">Histórico de Pesagem (por animal)</option>
                                        <option value="historico_vacinacao">Histórico de Vacinação (por animal)</option>
                                        <option value="vacinacao_por_periodo">Vacinação por Período</option>
                                    </select>
                                </div>

                                {{-- Filtro: Lote --}}
                                @if($tipoRelatorio === 'animais_por_lote')
                                <div class="col-span-1 md:col-span-2">
                                    <label for="loteId" class="block text-sm font-medium text-gray-700">Selecione o Lote</label>
                                    <select wire:model="loteId" id="loteId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Selecione...</option>
                                        @foreach($lotes as $lote)
                                        <option value="{{ $lote->id }}">{{ $lote->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('loteId') <span class="text-red-500 text-xs mt-1">É necessário selecionar um lote.</span> @enderror
                                </div>
                                @endif

                                {{-- Filtro: Animal --}}
                                @if($tipoRelatorio === 'historico_pesagem' || $tipoRelatorio === 'historico_vacinacao')
                                <div class="col-span-1 md:col-span-2">
                                    <label for="animalId" class="block text-sm font-medium text-gray-700">Selecione o Animal</label>
                                    <select wire:model="animalId" id="animalId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Selecione...</option>
                                        @foreach($animais as $animal)
                                        <option value="{{ $animal->id }}">{{ $animal->identificacao}}</option>
                                        @endforeach
                                    </select>
                                    @error('animalId') <span class="text-red-500 text-xs mt-1">É necessário selecionar um animal.</span> @enderror
                                </div>
                                @endif

                                {{-- Filtro: Período --}}
                                @if($tipoRelatorio === 'vacinacao_por_periodo')
                                <div class="col-span-1">
                                    <label for="data_inicio" class="block text-sm font-medium text-gray-700">Data de Início</label>
                                    <input type="date" wire:model="data_inicio" id="data_inicio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div class="col-span-1">
                                    <label for="data_fim" class="block text-sm font-medium text-gray-700">Data de Fim</label>
                                    <input type="date" wire:model="data_fim" id="data_fim" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                @endif

                                {{-- Botão Gerar Relatório --}}
                                <div class="self-end col-span-1">
                                    <button wire:click="gerarRelatorio" class="w-full inline-flex justify-center items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium text-white bg-if-green hover:bg-if-green-700">
                                        Gerar Relatório
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Seção de Resultados --}}
                    @if(!empty($dadosRelatorio))
                    <div wire:key="{{ $tipoRelatorio.($animalId ?? '').($loteId ?? '') }}">
                        <div x-data="{
                                imprimirRelatorio() {
                                    const areaImpressao = document.getElementById('area-impressao');
                                    if (!areaImpressao) return;

                                    const tituloRelatorio = areaImpressao.querySelector('h3').textContent;
                                    const tabelaConteudo = areaImpressao.querySelector('table').outerHTML;
                                    
                                    const telaImpressao = window.open('', '_blank');

                                    telaImpressao.document.write(`
                                        <html>
                                            <head>
                                                <title>Relatório - ArcaIF</title>
                                                <style>
                                                    body { font-family: Arial, sans-serif; margin: 20px; }
                                                    h1, h2 { color: #333; }
                                                    h1 { font-size: 24px; margin-bottom: 0; }
                                                    h2 { font-size: 20px; font-weight: normal; margin-top: 5px; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
                                                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                                                    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
                                                    th { background-color: #f2f2f2; font-weight: bold; }
                                                    tr:nth-child(even) { background-color: #f9f9f9; }
                                                </style>
                                            </head>
                                            <body>
                                                <h1>Relatório - ArcaIF</h1>
                                                <h2>${tituloRelatorio}</h2>
                                                ${tabelaConteudo}
                                            </body>
                                        </html>
                                    `);
                                    
                                    telaImpressao.document.close();
                                    
                                    setTimeout(() => {
                                        telaImpressao.focus();
                                        telaImpressao.print();
                                        telaImpressao.close();
                                    }, 250);
                                }
                            }">
                            <div id="area-impressao">
                                <div class="flex justify-between items-center my-6">
                                    {{-- O Título agora está centralizado e sem a div externa --}}
                                    <div class="w-full text-center">
                                        @if($tipoRelatorio === 'animais_por_lote' && $loteSelecionado)
                                            <h3 class="text-xl font-bold">Relatório de Animais do Lote: <span class="text-if-green-600">{{ $loteSelecionado->nome }}</span></h3>
                                        @elseif($tipoRelatorio === 'historico_pesagem' && $animalSelecionado)
                                            <h3 class="text-xl font-bold">Relatório de Pesagem: <span class="text-if-green-600">{{ $animalSelecionado->identificacao }}</span></h3>
                                        @elseif($tipoRelatorio === 'historico_vacinacao' && $animalSelecionado)
                                            <h3 class="text-xl font-bold">Relatório de Vacinação: <span class="text-if-green-600">{{ $animalSelecionado->identificacao }}</span></h3>
                                        @elseif($tipoRelatorio === 'vacinacao_por_periodo')
                                            <h3 class="text-xl font-bold">Relatório de Vacinação de {{ \Carbon\Carbon::parse($data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($data_fim)->format('d/m/Y') }}</h3>
                                        @endif
                                        <p class="text-sm text-gray-500">Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <button @click="imprimirRelatorio()" class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 print:hidden">
                                        <i class="fas fa-print mr-2"></i> Imprimir
                                    </button>
                                </div>

                                {{-- Tabela: Animais por Lote --}}
                                @if($tipoRelatorio === 'animais_por_lote')
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
                                                {{-- CORRIGIDO AQUI --}}
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
                                @endif

                                {{-- Tabela: Histórico de Pesagem --}}
                                @if($tipoRelatorio === 'historico_pesagem')
                                <div class="border-t border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data da Pesagem</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peso (Valor)</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($dadosRelatorio as $movimentacao)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $movimentacao->valor }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $movimentacao->descricao }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Nenhuma pesagem encontrada para este animal.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @endif

                                {{-- Tabela: Histórico de Vacinação --}}
                                @if($tipoRelatorio === 'historico_vacinacao')
                                <div class="border-t border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data da Vacinação</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacina (Valor)</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($dadosRelatorio as $movimentacao)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $movimentacao->valor }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $movimentacao->descricao }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Nenhuma vacinação encontrada para este animal.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @endif

                                {{-- Tabela: Histórico de Vacinação por Período --}}
                                @if($tipoRelatorio === 'vacinacao_por_periodo')
                                <div class="border-t border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacina (Valor)</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($dadosRelatorio as $movimentacao)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{-- CORRIGIDO AQUI --}}
                                                    <a href="{{ route('animais.show', $movimentacao->animal) }}" class="text-if-green-600 hover:underline">
                                                        {{ $movimentacao->animal->identificacao }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $movimentacao->valor }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $movimentacao->descricao }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Nenhuma vacinação encontrada neste período.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>