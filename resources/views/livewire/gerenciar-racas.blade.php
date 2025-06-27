<div>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gerenciar Raças') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Cabeçalho do Card --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Lista de Raças</h3>
                            <p class="mt-1 text-sm text-gray-500">Adicione ou edite as raças de cada espécie.</p>
                        </div>
                        <div class="md:text-right">
                            <button wire:click="abrirModal()" type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                Nova Raça
                            </button>
                        </div>
                    </div>

                    {{-- Mensagens de Feedback --}}
                    @if (session()->has('sucesso'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Sucesso</p>
                            <p>{{ session('sucesso') }}</p>
                        </div>
                    @endif
                    @if (session()->has('erro'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Erro</p>
                            <p>{{ session('erro') }}</p>
                        </div>
                    @endif

                    {{-- Tabela de Raças --}}
                    <div class="border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome da Raça</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Espécie</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($racas as $raca)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $raca->nome }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $raca->especie->nome }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <button wire:click="editar({{ $raca->id }})" class="text-indigo-600 hover:text-indigo-900" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                            <button wire:click="deletar({{ $raca->id }})" wire:confirm="Tem certeza que deseja remover esta raça?" class="text-red-600 hover:text-red-900" title="Deletar"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="text-center">
                                                <i class="fas fa-dna fa-3x text-gray-300"></i>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma raça encontrada</h3>
                                                <p class="mt-1 text-sm text-gray-500">Comece cadastrando uma nova raça.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($racas->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200">
                            {{ $racas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Adicionar/Editar Raça --}}
    @if ($modalAberto)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="salvar">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ $racaId ? 'Editar Raça' : 'Nova Raça' }}</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="especie_id_modal" class="block text-sm font-medium text-gray-700">Espécie</label>
                                <select wire:model="especie_id" id="especie_id_modal" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione uma espécie</option>
                                    @foreach($especies as $especie)
                                        <option value="{{ $especie->id }}">{{ $especie->nome }}</option>
                                    @endforeach
                                </select>
                                @error('especie_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="nome_modal" class="block text-sm font-medium text-gray-700">Nome da Raça</label>
                                <input type="text" wire:model="nome" id="nome_modal" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: Nelore">
                                @error('nome') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Salvar</button>
                        <button type="button" wire:click="fecharModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
