<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Previsão de Consumo e Custos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Filtro e Cartões de Resumo --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        <div class="md:col-span-1">
                            <label for="filtroEspecie" class="block text-sm font-medium text-gray-700">Filtrar por Espécie</label>
                            <select wire:model.live="filtroEspecie" id="filtroEspecie" class="mt-1 block w-full rounded-md shadow-sm">
                                <option value="">Todas as Espécies</option>
                                @foreach($todasEspecies as $especie)
                                    <option value="{{ $especie->id }}">{{ $especie->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg text-center">
                            <p class="text-sm font-medium text-gray-500">Total de Animais</p>
                            <p class="mt-1 text-3xl font-bold text-if-green-600">{{ $totalAnimais }}</p>
                        </div>
                        <div class="p-4 bg-gray-100 rounded-lg text-center md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Custo Total Anual Previsto</p>
                            <p class="mt-1 text-3xl font-bold text-if-green-600">R$ {{ number_format($custoTotalAno, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabela 1: Previsão por Categoria (como na sua imagem) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Detalhes por Categoria</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase">Categoria / Fórmula</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium uppercase">Nº Animais</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Consumo Cab/Dia (kg)</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Consumo Total/Dia (kg)</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Custo Cab/Dia (R$)</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Custo Total/Mês (R$)</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Custo Total/Ano (R$)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($previsaoPorCategoria as $prev)
                                <tr>
                                    <td class="px-4 py-2 text-sm"><p class="font-semibold">{{ $prev['nome'] }}</p><p class="text-xs text-gray-500">{{ $prev['formula'] }}</p></td>
                                    <td class="px-4 py-2 text-sm text-center">{{ $prev['numero_animais'] }}</td>
                                    <td class="px-4 py-2 text-sm text-right">{{ number_format($prev['consumo_animal_dia'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm text-right font-bold">{{ number_format($prev['consumo_total_dia'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm text-right">R$ {{ number_format($prev['custo_animal_dia'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm text-right font-bold">R$ {{ number_format($prev['custo_total_mes'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm text-right font-bold text-if-green-700">R$ {{ number_format($prev['custo_total_ano'], 2, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center py-4 text-gray-500">Selecione uma espécie ou aloque animais em categorias para ver a previsão.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tabela 2: Necessidade de Ingredientes --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Necessidade Anual de Ingredientes</h3>
                     <p class="mt-1 text-sm text-gray-500">Previsão baseada no consumo das categorias filtradas acima.</p>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase">Ingrediente</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Preço/kg (R$)</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Total (kg/ano)</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Sacos 50kg/ano</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium uppercase">Valor Total (R$/ano)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($necessidadeDeIngredientes as $ing)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-semibold">{{ $ing['nome'] }}</td>
                                    <td class="px-4 py-2 text-sm text-right">R$ {{ number_format($ing['preco_kg'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm text-right">{{ number_format($ing['total_kg_ano'], 2, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm text-right">{{ number_format($ing['sacos_50kg_ano'], 1, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm font-bold text-right text-if-green-700">R$ {{ number_format($ing['valor_total_ano'], 2, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-gray-500">Nenhum dado para calcular a necessidade de ingredientes.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>