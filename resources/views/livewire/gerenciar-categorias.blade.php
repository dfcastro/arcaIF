<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Categorias de Animais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="sm:flex sm:items-center sm:justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Categorias Cadastradas</h3>
                            <p class="mt-1 text-sm text-gray-500">Defina os grupos de animais, as suas dietas e o consumo diário.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                             <x-primary-button wire:click="abrirModal()">
                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                Nova Categoria
                            </x-primary-button>
                        </div>
                    </div>

                    @if (session()->has('sucesso'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert"><p>{{ session('sucesso') }}</p></div>
                    @endif
                    @if (session()->has('erro'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert"><p>{{ session('erro') }}</p></div>
                    @endif

                    <div class="border-t border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoria</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Espécie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fórmula</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Consumo (kg/dia)</th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($categorias as $categoria)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $categoria->nome }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $categoria->especie->nome }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $categoria->formulaRacao->nome_formula }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">{{ number_format($categoria->consumo_diario_kg, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                                <button wire:click="editar({{ $categoria->id }})" class="text-if-green-600 hover:text-if-green-900" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                                <button wire:click="confirmarDelecao({{ $categoria->id }})" class="text-red-600 hover:text-red-900" title="Deletar"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-12 text-center">
                                            <div class="text-center">
                                                <i class="fas fa-tags fa-3x text-gray-300"></i>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma categoria encontrada</h3>
                                                <p class="mt-1 text-sm text-gray-500">Comece por cadastrar a sua primeira categoria de animais.</p>
                                            </div>
                                        </td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($categorias->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200">{{ $categorias->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Adicionar/Editar Categoria --}}
    @if ($modalAberto)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="salvar">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                         <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-if-green-100 sm:mx-0 sm:h-10 sm:w-10"><i class="fas fa-tags text-if-green-600"></i></div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $categoriaId ? 'Editar Categoria' : 'Nova Categoria' }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Preencha as informações da categoria.</p>
                            </div>
                        </div>
                        <div class="mt-4 border-t pt-4 space-y-4">
                            <div><label for="nome_cat" class="block text-sm font-medium">Nome da Categoria</label><input type="text" wire:model="nome" id="nome_cat" class="mt-1 block w-full rounded-md" placeholder="Ex: Vacas em Lactação">@error('nome')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                            <div><label for="especie_cat" class="block text-sm font-medium">Espécie</label><select wire:model.live="especie_id" id="especie_cat" class="mt-1 block w-full rounded-md"><option value="">Selecione...</option>@foreach($todasEspecies as $especie)<option value="{{$especie->id}}">{{$especie->nome}}</option>@endforeach</select>@error('especie_id')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                            <div><label for="formula_cat" class="block text-sm font-medium">Fórmula da Ração</label><select wire:model="formula_racao_id" id="formula_cat" class="mt-1 block w-full rounded-md" @if(!$especie_id) disabled @endif><option value="">Selecione uma espécie primeiro...</option>@foreach($formulasDisponiveis as $formula)<option value="{{$formula->id}}">{{$formula->nome_formula}}</option>@endforeach</select>@error('formula_racao_id')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                            <div><label for="consumo_cat" class="block text-sm font-medium">Consumo por Animal (kg/dia)</label><input type="number" step="0.1" wire:model="consumo_diario_kg" id="consumo_cat" class="mt-1 block w-full rounded-md">@error('consumo_diario_kg')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                            <div><label for="desc_cat" class="block text-sm font-medium">Descrição</label><textarea wire:model="descricao" id="desc_cat" rows="2" class="mt-1 block w-full rounded-md"></textarea></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button wire:loading.attr="disabled" wire:target="salvar"><svg wire:loading wire:target="salvar" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span wire:loading.remove wire:target="salvar">Salvar</span><span wire:loading wire:target="salvar">Salvando...</span></x-primary-button>
                        <x-secondary-button wire:click="fecharModal()" class="sm:mt-0 mt-3">Cancelar</x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    
    {{-- Modal de Confirmação de Exclusão --}}
    @if ($modalDelecaoAberto)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"><i class="fas fa-exclamation-triangle text-red-600"></i></div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Excluir Categoria</h3>
                            <div class="mt-2"><p class="text-sm text-gray-500">Tem a certeza de que deseja excluir esta categoria? Os animais associados a ela ficarão sem categoria definida.</p></div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <x-danger-button wire:click="deletar()" class="sm:ml-3">Confirmar Exclusão</x-danger-button>
                    <x-secondary-button wire:click="$set('modalDelecaoAberto', false)" class="sm:mt-0 mt-3">Cancelar</x-secondary-button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>