<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gerenciar Espécies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Cabeçalho do Card --}}
                    <div class="sm:flex sm:items-center sm:justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Lista de Espécies</h3>
                            <p class="mt-1 text-sm text-gray-500">Adicione, edite ou remova as espécies de animais.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                             <x-primary-button wire:click="abrirModal()">
                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                Nova Espécie
                            </x-primary-button>
                        </div>
                    </div>

                    {{-- Mensagens de Feedback --}}
                    @if (session()->has('sucesso'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert"><p>{{ session('sucesso') }}</p></div>
                    @endif
                    @if (session()->has('erro'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert"><p>{{ session('erro') }}</p></div>
                    @endif

                    {{-- Tabela de Espécies --}}
                    <div class="border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($especies as $especie)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $especie->nome }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <button wire:click="editar({{ $especie->id }})" class="text-if-green-600 hover:text-if-green-900" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                            <button wire:click="confirmarDelecao({{ $especie->id }})" class="text-red-600 hover:text-red-900" title="Deletar"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-6 py-12 text-center">
                                        <div class="text-center">
                                            <i class="fas fa-book-open fa-3x text-gray-300"></i>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma espécie encontrada</h3>
                                            <p class="mt-1 text-sm text-gray-500">Comece por cadastrar uma nova espécie.</p>
                                        </div>
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($especies->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200">{{ $especies->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Adicionar/Editar Espécie --}}
    @if ($modalAberto)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="salvar">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-if-green-100 sm:mx-0 sm:h-10 sm:w-10"><i class="fas fa-book-open text-if-green-600"></i></div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ $especieId ? 'Editar Espécie' : 'Nova Espécie' }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Insira o nome da espécie para adicioná-la ao sistema.</p>
                            </div>
                        </div>
                        <div class="mt-4 border-t pt-4">
                            <label for="nome" class="block text-sm font-medium text-gray-700">Nome da Espécie</label>
                            <input type="text" wire:model="nome" id="nome" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-if-green-500 focus:ring-if-green-500" placeholder="Ex: Caprino">
                            @error('nome') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button wire:loading.attr="disabled" wire:target="salvar">
                            <svg wire:loading wire:target="salvar" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
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
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"><i class="fas fa-exclamation-triangle text-red-600"></i></div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Excluir Espécie</h3>
                            <div class="mt-2"><p class="text-sm text-gray-500">Tem a certeza de que deseja excluir esta espécie? Esta ação não pode ser revertida.</p></div>
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