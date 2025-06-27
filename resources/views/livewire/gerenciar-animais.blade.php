{{--
    ARQUIVO: resources/views/livewire/gerenciar-animais.blade.php
--}}
<div>
    {{-- Esta seção preenche o cabeçalho cinza no topo da página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Controle de Animais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Cabeçalho do Card --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center mb-6">
                        {{-- Coluna da Esquerda (Título) --}}
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Lista de Animais</h3>
                            <p class="mt-1 text-sm text-gray-500">Gerencie todos os animais cadastrados no sistema.</p>
                        </div>
                        {{-- Coluna da Direita (Botão) --}}
                        <div class="md:text-right">
                            <button wire:click="abrirModal()" type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                Novo Animal
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

                    {{-- Busca --}}
                    <div class="mb-4">
                        <input 
                            wire:model.live.debounce.300ms="termoBusca"
                            type="text" 
                            placeholder="Buscar por identificação do animal..." 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    {{-- Tabela de Animais --}}
                    <div class="border-t border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identificação</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Espécie</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raça</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nascimento</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Ações</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($animais as $animal)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $animal->identificacao }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->especie->nome }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->raca->nome ?? 'N/D' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->sexo }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($animal->data_nascimento)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span @class([
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                    'bg-green-100 text-green-800' => $animal->status == 'Ativo',
                                                    'bg-yellow-100 text-yellow-800' => $animal->status == 'Vendido',
                                                    'bg-red-100 text-red-800' => $animal->status == 'Óbito',
                                                ])>
                                                    {{ $animal->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                                <button wire:click="editar({{ $animal->id }})" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <button wire:click="deletar({{ $animal->id }})" wire:confirm="Tem certeza que deseja remover este animal?" class="text-red-600 hover:text-red-900" title="Deletar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 text-center">
                                                <div class="text-center">
                                                    <i class="fas fa-paw fa-3x text-gray-300"></i>
                                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum animal encontrado</h3>
                                                    <p class="mt-1 text-sm text-gray-500">Comece cadastrando um novo animal.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($animais->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200">
                            {{ $animais->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Adicionar/Editar Animal --}}
    @if ($modalAberto)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="salvar">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            {{ $animalId ? 'Editar Animal' : 'Cadastrar Novo Animal' }}
                        </h3>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            {{-- Campo Espécie --}}
                            <div class="col-span-1">
                                <label for="especie_id" class="block text-sm font-medium text-gray-700">Espécie</label>
                                <select wire:model.live="especie_id" id="especie_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione...</option>
                                    @foreach($especies as $especie)
                                        <option value="{{ $especie->id }}">{{ $especie->nome }}</option>
                                    @endforeach
                                </select>
                                @error('especie_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Raça --}}
                            <div class="col-span-1">
                                <label for="raca_id" class="block text-sm font-medium text-gray-700">Raça</label>
                                <select wire:model="raca_id" id="raca_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @if(empty($racas)) bg-gray-100 cursor-not-allowed @endif" @if(empty($racas)) disabled @endif>
                                    <option value="">Selecione...</option>
                                    @foreach($racas as $raca)
                                        <option value="{{ $raca->id }}">{{ $raca->nome }}</option>
                                    @endforeach
                                </select>
                                @error('raca_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Identificação --}}
                            <div class="md:col-span-2">
                                <label for="identificacao" class="block text-sm font-medium text-gray-700">Identificação</label>
                                <input type="text" wire:model="identificacao" id="identificacao" placeholder="Ex: Brinco 123, nome 'Mimoso'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('identificacao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Data de Nascimento --}}
                            <div>
                                <label for="data_nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                                <input type="date" wire:model="data_nascimento" id="data_nascimento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('data_nascimento') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Sexo --}}
                            <div>
                                <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
                                <select wire:model="sexo" id="sexo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Selecione...</option>
                                    <option value="Macho">Macho</option>
                                    <option value="Fêmea">Fêmea</option>
                                </select>
                                @error('sexo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Status --}}
                            <div class="md:col-span-2">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Ativo">Ativo</option>
                                    <option value="Vendido">Vendido</option>
                                    <option value="Óbito">Óbito</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Campo Observações --}}
                            <div class="md:col-span-2">
                                <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea wire:model="observacoes" id="observacoes" rows="3" placeholder="Qualquer informação adicional sobre o animal." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                @error('observacoes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Salvar
                        </button>
                        <button type="button" wire:click="fecharModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
