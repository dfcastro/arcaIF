<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Controle de Animais') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="sm:flex sm:items-center sm:justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Lista de Animais</h3>
                            <p class="mt-1 text-sm text-gray-500">Filtre, ordene e gerencie todos os animais cadastrados.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <x-primary-button wire:click="abrirModal()">
                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                Novo Animal
                            </x-primary-button>
                        </div>
                    </div>

                    @if (session()->has('sucesso'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('sucesso') }}</p>
                    </div>
                    @endif

                    {{-- SEÇÃO DE FILTROS --}}
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 bg-gray-50 rounded-lg border">
                        <div class="md:col-span-2">
                            <label for="busca" class="text-sm font-medium text-gray-700">Buscar por Identificação</label>
                            <input wire:model.live.debounce.300ms="termoBusca" id="busca" type="text" placeholder="Digite aqui..." class="mt-1 w-full rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="filtroEspecie" class="text-sm font-medium text-gray-700">Espécie</label>
                            <select wire:model.live="filtroEspecie" id="filtroEspecie" class="mt-1 w-full rounded-md shadow-sm">
                                <option value="">Todas</option>
                                @foreach($especies as $especie)
                                <option value="{{ $especie->id }}">{{ $especie->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="filtroLocalizacao" class="text-sm font-medium text-gray-700">Localização</label>
                            <select wire:model.live="filtroLocalizacao" id="filtroLocalizacao" class="mt-1 w-full rounded-md shadow-sm">
                                <option value="">Todas</option>
                                @foreach($localizacoes as $localizacao)
                                <option value="{{ $localizacao->id }}">{{ $localizacao->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="self-end">
                            <x-secondary-button wire:click="limparFiltros" class="w-full justify-center">Limpar Filtros</x-secondary-button>
                        </div>
                    </div>

                    {{-- Tabela de Animais com Ordenação --}}
                    <div class="border-t border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">
                                            <button wire:click="ordenarPor('identificacao')" class="flex items-center space-x-1 uppercase text-xs font-medium text-gray-500">
                                                <span>Identificação</span>
                                                @if ($campoOrdenacao === 'identificacao')<i class="fas {{ $direcaoOrdenacao === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>@else<i class="fas fa-sort text-gray-300"></i>@endif
                                            </button>
                                        </th>
                                        <th class="px-6 py-3">
                                            <button wire:click="ordenarPor('especie_id')" class="flex items-center space-x-1 uppercase text-xs font-medium text-gray-500">
                                                <span>Espécie</span>
                                                @if ($campoOrdenacao === 'especie_id')<i class="fas {{ $direcaoOrdenacao === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>@else<i class="fas fa-sort text-gray-300"></i>@endif
                                            </button>
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raça</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localização</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexo</th>
                                        <th class="px-6 py-3">
                                            <button wire:click="ordenarPor('data_nascimento')" class="flex items-center space-x-1 uppercase text-xs font-medium text-gray-500">
                                                <span>Nascimento</span>
                                                @if ($campoOrdenacao === 'data_nascimento')<i class="fas {{ $direcaoOrdenacao === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>@else<i class="fas fa-sort text-gray-300"></i>@endif
                                            </button>
                                        </th>
                                        <th class="px-6 py-3">
                                            <button wire:click="ordenarPor('status')" class="flex items-center space-x-1 uppercase text-xs font-medium text-gray-500">
                                                <span>Status</span>
                                                @if ($campoOrdenacao === 'status')<i class="fas {{ $direcaoOrdenacao === 'asc' ? 'fa-sort-up' : 'fa-sort-down' }}"></i>@else<i class="fas fa-sort text-gray-300"></i>@endif
                                            </button>
                                        </th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($animais as $animal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><a href="{{ route('animais.show', $animal) }}" class="text-if-green-600 hover:underline">{{ $animal->identificacao }}</a></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->especie->nome }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->raca->nome ?? 'N/D' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->localizacao->nome ?? 'N/D' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->categoria->nome ?? 'N/D' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $animal->sexo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($animal->data_nascimento)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span @class([ 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full' , 'bg-green-100 text-green-800'=> $animal->status == 'Ativo',
                                                'bg-yellow-100 text-yellow-800' => $animal->status == 'Vendido',
                                                'bg-purple-100 text-purple-800' => $animal->status == 'Doação', // <-- NOVA LINHA 'bg-red-100 text-red-800'=> $animal->status == 'Óbito',
                                                    ])>
                                                    {{ $animal->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <button wire:click="editar({{ $animal->id }})" class="text-if-green-600 hover:text-if-green-900" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                            @can('manage-system')
                                            <button wire:click="confirmarDelecao({{ $animal->id }})" class="text-red-600 hover:text-red-900" title="Deletar"><i class="fas fa-trash-alt"></i></button>
                                            @endcan
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center">
                                            <div class="text-center">
                                                <i class="fas fa-search fa-3x text-gray-300"></i>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum animal encontrado</h3>
                                                <p class="mt-1 text-sm text-gray-500">Tente ajustar a sua busca ou limpar os filtros.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($animais->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200">{{ $animais->links() }}</div>
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
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-if-green-100 sm:mx-0 sm:h-10 sm:w-10"><i class="fas fa-paw text-if-green-600"></i></div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ $animalId ? 'Editar Animal' : 'Cadastrar Novo Animal' }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Preencha as informações abaixo.</p>
                            </div>
                        </div>
                        <div class="mt-4 border-t pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="col-span-1"><label class="block text-sm font-medium">Espécie</label><select wire:model.live="especie_id" class="mt-1 block w-full rounded-md">
                                    <option value="">Selecione...</option>@foreach ($especies as $especie)<option value="{{ $especie->id }}">{{ $especie->nome }}</option>@endforeach
                                </select>@error('especie_id')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                            <div class="col-span-1"><label class="block text-sm font-medium">Raça</label><select wire:model="raca_id" class="mt-1 block w-full rounded-md" @if(empty($racas)) disabled @endif>
                                    <option value="">Selecione...</option>@foreach ($racas as $raca)<option value="{{ $raca->id }}">{{ $raca->nome }}</option>@endforeach
                                </select>@error('raca_id')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>

                            <div class="col-span-1">
                                <label for="pai_id" class="block text-sm font-medium text-gray-700">Pai (Reprodutor)</label>
                                <select wire:model="pai_id" id="pai_id" class="mt-1 block w-full rounded-md" @if(!$especie_id) disabled @endif>
                                    <option value="">Não identificado</option>
                                    @foreach ($paisDisponiveis as $pai)
                                    <option value="{{ $pai->id }}">{{ $pai->identificacao }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label for="mae_id" class="block text-sm font-medium text-gray-700">Mãe (Matriz)</label>
                                <select wire:model="mae_id" id="mae_id" class="mt-1 block w-full rounded-md" @if(!$especie_id) disabled @endif>
                                    <option value="">Não identificada</option>
                                    @foreach ($maesDisponiveis as $mae)
                                    <option value="{{ $mae->id }}">{{ $mae->identificacao }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2"><label class="block text-sm font-medium">Localização</label><select wire:model="localizacao_id" class="mt-1 block w-full rounded-md">
                                    <option value="">Nenhuma</option>@foreach ($localizacoes as $localizacao)<option value="{{ $localizacao->id }}">{{ $localizacao->nome }}</option>@endforeach
                                </select></div>
                            <div class="md:col-span-2"><label class="block text-sm font-medium">Categoria (Dieta)</label><select wire:model="categoria_animal_id" class="mt-1 block w-full rounded-md" @if(!$especie_id) disabled @endif>
                                    <option value="">Selecione uma dieta</option>@foreach ($categoriasDisponiveis as $categoria)<option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>@endforeach
                                </select>@error('categoria_animal_id')<span class="text-red-500 text-xs mt-1">{{$message}}</span>@enderror</div>

                            <div class="md:col-span-2"><label class="block text-sm font-medium">Identificação</label><input type="text" wire:model="identificacao" placeholder="Ex: Brinco 123, nome 'Mimoso'" class="mt-1 block w-full rounded-md">@error('identificacao')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>

                            <div><label class="block text-sm font-medium">Data de Nascimento</label><input type="date" wire:model="data_nascimento" class="mt-1 block w-full rounded-md">@error('data_nascimento')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>

                            <div><label class="block text-sm font-medium">Sexo</label><select wire:model="sexo" class="mt-1 block w-full rounded-md">
                                    <option value="">Selecione...</option>
                                    <option value="Macho">Macho</option>
                                    <option value="Fêmea">Fêmea</option>
                                </select>@error('sexo')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>

                            <div class="md:col-span-2"><label class="block text-sm font-medium">Status</label><select wire:model="status" class="mt-1 block w-full rounded-md">
                                    <option value="Ativo">Ativo</option>
                                    <option value="Vendido">Vendido</option>
                                    <option value="Doação">Doação</option>
                                    <option value="Óbito">Óbito</option>
                                </select>@error('status')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>

                            <div class="md:col-span-2"><label class="block text-sm font-medium">Observações</label><textarea wire:model="observacoes" rows="3" placeholder="Qualquer informação adicional sobre o animal." class="mt-1 block w-full rounded-md"></textarea>@error('observacoes')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button wire:loading.attr="disabled" wire:target="salvar"><svg wire:loading wire:target="salvar" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg><span wire:loading.remove wire:target="salvar">Salvar</span><span wire:loading wire:target="salvar">Salvando...</span></x-primary-button>
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Excluir Animal</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Tem a certeza de que deseja excluir este animal? Todos os dados históricos (pesagens, vacinas, etc.) serão perdidos. Esta ação não pode ser revertida.</p>
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