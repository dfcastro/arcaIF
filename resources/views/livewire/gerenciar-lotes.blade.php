<div>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gerenciar Lotes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Cabeçalho e Mensagens de Feedback --}}
                    <div class="sm:flex sm:items-center sm:justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Lista de Lotes</h3>
                            <p class="mt-1 text-sm text-gray-500">Agrupe os seus animais em diferentes lotes.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                             <x-primary-button wire:click="abrirModal()">
                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                Novo Lote
                            </x-primary-button>
                        </div>
                    </div>
                    @if (session()->has('sucesso'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('sucesso') }}</p>
                    </div>
                    @endif
                    @if (session()->has('erro'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p>{{ session('erro') }}</p>
                    </div>
                    @endif

                    {{-- Lista de Lotes em Cards --}}
                    <div class="border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse ($lotes as $lote)
                                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <a href="{{ route('lotes.show', $lote) }}" class="text-if-green-600 hover:underline">
                                                    <h4 class="text-lg font-semibold">{{ $lote->nome }}</h4>
                                                </a>
                                                <p class="text-sm text-gray-600 mt-1">{{ $lote->descricao ?? 'Sem descrição' }}</p>
                                            </div>
                                            <div class="flex-shrink-0 ml-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-if-green-100 text-if-green-800">
                                                    {{ $lote->animais_count }} {{ \Illuminate\Support\Str::plural('animal', $lote->animais_count) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex justify-end items-center space-x-3 border-t pt-4">
                                            <a href="{{ route('lotes.show', $lote) }}" class="text-sm font-medium text-if-green-600 hover:text-if-green-800" title="Ver Detalhes e Gerenciar Animais">
                                                <i class="fas fa-tasks mr-1"></i> Gerenciar
                                            </a>
                                            <button wire:click="editar({{ $lote->id }})" class="text-sm font-medium text-gray-500 hover:text-gray-700" title="Editar Nome/Descrição">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button wire:click="confirmarDelecao({{ $lote->id }})" class="text-sm font-medium text-red-500 hover:text-red-700" title="Deletar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                {{-- Estado Vazio --}}
                                <div class="col-span-full">
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                          <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                        </svg>
                                        <h3 class="mt-2 text-lg font-medium text-gray-900">Nenhum lote cadastrado</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                          Comece por adicionar o seu primeiro lote para organizar os animais.
                                        </p>
                                        <div class="mt-6">
                                            <x-primary-button wire:click="abrirModal()">
                                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                                Cadastrar Primeiro Lote
                                            </x-primary-button>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @if($lotes->hasPages())
                        <div class="bg-white px-4 py-3 mt-6 border-t border-gray-200">
                            {{ $lotes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Adicionar/Editar Lote --}}
    @if ($modalAberto)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="salvar">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-if-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-box-open text-if-green-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ $loteId ? 'Editar Lote' : 'Novo Lote' }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Insira as informações do lote.</p>
                            </div>
                        </div>
                        <div class="mt-4 border-t pt-4 space-y-4">
                            <div>
                                <label for="nome_lote" class="block text-sm font-medium text-gray-700">Nome do Lote</label>
                                <input type="text" wire:model="nome" id="nome_lote" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-if-green-500 focus:ring-if-green-500" placeholder="Ex: Lote de Engorda 2025">
                                @error('nome') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="descricao_lote" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea wire:model="descricao" id="descricao_lote" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Qualquer informação adicional sobre o lote."></textarea>
                                @error('descricao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button wire:loading.attr="disabled" wire:target="salvar">
                            <svg wire:loading wire:target="salvar" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="salvar">Salvar</span>
                            <span wire:loading wire:target="salvar">Salvando...</span>
                        </x-primary-button>
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
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Excluir Lote</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Tem a certeza de que deseja excluir este lote? Esta ação não pode ser revertida.</p>
                            </div>
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