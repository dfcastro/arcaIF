<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulador de Rações') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Nova Fórmula de Ração</h3>
                        <p class="mt-1 text-sm text-gray-500">Crie uma nova dieta misturando ingredientes e veja a composição e o custo em tempo real.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Coluna da Esquerda: Dados da Fórmula e Ingredientes --}}
                        <div>
                            <div class="space-y-4">
                                <div><label class="block text-sm font-medium">Nome da Fórmula</label><input type="text" wire:model.lazy="nome_formula" class="mt-1 block w-full rounded-md">@error('nome_formula')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                                <div><label class="block text-sm font-medium">Espécie-alvo</label><select wire:model.lazy="especie_id" class="mt-1 block w-full rounded-md"><option value="">Selecione...</option>@foreach($todasEspecies as $especie)<option value="{{$especie->id}}">{{$especie->nome}}</option>@endforeach</select>@error('especie_id')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                                <div><label class="block text-sm font-medium">Descrição (Opcional)</label><textarea wire:model.lazy="descricao" rows="2" class="mt-1 block w-full rounded-md"></textarea></div>
                            </div>
                            
                            <hr class="my-6">
                            
                            <h4 class="text-md font-medium text-gray-800 mb-2">Composição da Fórmula</h4>
                            @error('ingredientesDaFormula')<span class="text-red-500 text-sm font-semibold">{{$message}}</span>@enderror
                            
                            @foreach($ingredientesDaFormula as $index => $item)
                            <div class="grid grid-cols-12 gap-3 items-start mt-2 py-2 @if(!$loop->last) border-b border-gray-100 @endif" wire:key="item-{{ $index }}">
                                <div class="col-span-7">
                                    <select wire:model="ingredientesDaFormula.{{$index}}.ingrediente_id" class="block w-full rounded-md text-sm">
                                        <option value="">Selecione...</option>
                                        @foreach($todosIngredientes as $ingrediente)
                                        <option value="{{$ingrediente->id}}">{{$ingrediente->nome}}</option>
                                        @endforeach
                                    </select>
                                    {{-- CORREÇÃO: Adicionando o @error para o ingrediente --}}
                                    @error('ingredientesDaFormula.'.$index.'.ingrediente_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-3">
                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="ingredientesDaFormula.{{$index}}.percentual_inclusao" class="block w-full rounded-md text-sm" placeholder="%">
                                    {{-- CORREÇÃO: Adicionando o @error para o percentual --}}
                                    @error('ingredientesDaFormula.'.$index.'.percentual_inclusao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-2 text-right pt-1">
                                    <button wire:click.prevent="removerIngrediente({{$index}})" class="text-gray-400 hover:text-red-600" title="Remover Ingrediente">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach

                            <button wire:click.prevent="adicionarIngrediente" type="button" class="mt-4 text-sm text-if-green-600 font-semibold hover:underline">
                                <i class="fas fa-plus mr-1"></i> Adicionar Ingrediente
                            </button>
                        </div>

                        {{-- Coluna da Direita: Resultados --}}
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <h4 class="font-semibold text-gray-800">Resultado da Formulação</h4>
                            <p class="text-xs text-gray-500 mb-4">Os valores são calculados com base na matéria natural.</p>
                            <table class="min-w-full">
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="font-bold {{ abs($total_inclusao - 100) > 0.01 ? 'text-red-600' : 'text-if-green-600' }}">
                                        <td class="py-2">Inclusão Total</td>
                                        <td class="py-2 text-right">{{ number_format($total_inclusao, 2, ',', '.') }} %</td>
                                    </tr>
                                    @error('total_inclusao')<tr><td colspan="2" class="text-xs text-red-500 -mt-2">{{$message}}</td></tr>@enderror
                                    
                                    <tr class="font-bold">
                                        <td class="py-2">Custo por kg da Ração</td>
                                        <td class="py-2 text-right">R$ {{ number_format($total_preco_kg, 4, ',', '.') }}</td>
                                    </tr>
                                    <tr><td colspan="2"><hr class="my-2"></td></tr>
                                    <tr><td class="py-1">Proteína Bruta (PB)</td><td class="py-1 text-right">{{ number_format($total_proteina_bruta, 2, ',', '.') }} %</td></tr>
                                    <tr><td class="py-1">Extrato Etéreo (EE)</td><td class="py-1 text-right">{{ number_format($total_extrato_etereo, 2, ',', '.') }} %</td></tr>
                                    <tr><td class="py-1">Fibra Bruta (FB)</td><td class="py-1 text-right">{{ number_format($total_fibra_bruta, 2, ',', '.') }} %</td></tr>
                                    <tr><td class="py-1">Matéria Mineral (MM)</td><td class="py-1 text-right">{{ number_format($total_materia_mineral, 2, ',', '.') }} %</td></tr>
                                    <tr><td class="py-1">Cálcio (Ca)</td><td class="py-1 text-right">{{ number_format($total_calcio, 2, ',', '.') }} %</td></tr>
                                    <tr><td class="py-1">Fósforo (P)</td><td class="py-1 text-right">{{ number_format($total_fosforo, 2, ',', '.') }} %</td></tr>
                                </tbody>
                            </table>
                            
                            <div class="mt-6">
                                <x-primary-button wire:click="salvarFormula" wire:loading.attr="disabled" class="w-full justify-center">
                                    <svg wire:loading wire:target="salvarFormula" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span wire:loading.remove wire:target="salvarFormula">Salvar Fórmula</span>
                                    <span wire:loading wire:target="salvarFormula">Salvando...</span>
                                </x-primary-button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>