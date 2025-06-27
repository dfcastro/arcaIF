<div>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ficha do Animal: <span class="text-indigo-600">{{ $animal->identificacao }}</span>
            </h2>
            <a href="{{ url('/animais') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                &larr; Voltar para a lista de animais
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Coluna da Esquerda: Dados do Animal --}}
            <div class="lg:col-span-1">
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Dados Cadastrais</h3>
                        <div class="mt-4 border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Espécie</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $animal->especie->nome }}</dd>
                                </div>
                                <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Raça</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $animal->raca->nome ?? 'Não definida' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $animal->sexo }}</dd>
                                </div>
                                <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nascimento</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($animal->data_nascimento)->format('d/m/Y') }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Idade</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ \Carbon\Carbon::parse($animal->data_nascimento)->age }} anos</dd>
                                </div>
                                 <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $animal->status }}</dd>
                                </div>
                                @if($animal->observacoes)
                                <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Observações</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $animal->observacoes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna da Direita: Histórico de Movimentações com ABAS --}}
            <div class="lg:col-span-2">
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @if (session()->has('sucesso'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4" role="alert">
                                <p>{{ session('sucesso') }}</p>
                            </div>
                        @endif

                        <h3 class="text-lg font-medium leading-6 text-gray-900">Adicionar Novo Evento</h3>
                        <form wire:submit.prevent="salvarMovimentacao" class="mt-4 border-t border-gray-200 py-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="data" class="block text-sm font-medium text-gray-700">Data</label>
                                    <input type="date" wire:model="data" id="data" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('data') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Evento</label>
                                    <select wire:model.live="tipo" id="tipo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option>Observação</option>
                                        <option>Pesagem</option>
                                        <option>Vacinação</option>
                                        <option>Medicação</option>
                                        <option>Venda</option>
                                        <option>Óbito</option>
                                    </select>
                                    @error('tipo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea wire:model="descricao" id="descricao" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Descreva o evento..."></textarea>
                                @error('descricao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="valor" class="block text-sm font-medium text-gray-700">Valor (Opcional)</label>
                                <input type="text" wire:model="valor" id="valor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: 520 kg, nome da vacina, R$ 2.500,00">
                                @error('valor') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="text-right">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    Registar Evento
                                </button>
                            </div>
                        </form>

                        {{-- NOVA SEÇÃO DE ABAS --}}
                        <div class="mt-6 border-t border-gray-200 pt-6">
                             <h3 class="text-lg font-medium leading-6 text-gray-900">Eventos Registados</h3>
                             <div class="mt-4">
                                <div class="sm:hidden">
                                    <label for="tabs" class="sr-only">Select a tab</label>
                                    <select wire:model.live="activeTab" id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option>Todos</option>
                                        <option>Pesagem</option>
                                        <option>Vacinação</option>
                                        <option>Medicação</option>
                                        <option>Observação</option>
                                    </select>
                                </div>
                                <div class="hidden sm:block">
                                    <div class="border-b border-gray-200">
                                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                            @foreach (['Todos', 'Pesagem', 'Vacinação', 'Medicação', 'Observação'] as $tab)
                                            <button wire:click="$set('activeTab', '{{ $tab }}')"
                                                @class([
                                                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                                    'border-indigo-500 text-indigo-600' => $activeTab === $tab,
                                                    'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== $tab,
                                                ])>
                                                {{ $tab }}
                                            </button>
                                            @endforeach
                                        </nav>
                                    </div>
                                </div>
                            </div>

                            {{-- LINHA DO TEMPO --}}
                            <div class="mt-6 flow-root">
                               <ul role="list" class="-mb-8">
                                    @php
                                        // Filtra a coleção de movimentações com base na aba ativa
                                        $movimentacoesFiltradas = $activeTab == 'Todos'
                                            ? $animal->movimentacoes
                                            : $animal->movimentacoes->where('tipo', $activeTab);
                                    @endphp
                                    @forelse ($movimentacoesFiltradas as $movimentacao)
                                        <li>
                                            <div class="relative pb-8">
                                                @if (!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white"><i class="fas fa-history text-white"></i></span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">
                                                                <span class="font-medium text-gray-900">{{ $movimentacao->tipo }}:</span>
                                                                {{ $movimentacao->descricao }}
                                                                @if($movimentacao->valor)
                                                                    <span class="font-semibold">({{ $movimentacao->valor }})</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time datetime="{{ $movimentacao->data }}">{{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y') }}</time>
                                                            <div class="mt-1">
                                                                 <button wire:click="startEditing({{ $movimentacao->id }})" class="text-blue-500 hover:text-blue-700" title="Editar"><i class="fas fa-edit"></i></button>
                                                                <button wire:click="deleteMovimentacao({{ $movimentacao->id }})" wire:confirm="Tem a certeza que quer remover este evento do histórico?" class="ml-2 text-red-500 hover:text-red-700" title="Remover"><i class="fas fa-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="py-4 text-center text-sm text-gray-500">Nenhum evento do tipo '{{$activeTab}}' encontrado.</li>
                                    @endforelse
                               </ul>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
    
    {{-- MODAL DE EDIÇÃO --}}
    @if ($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="updateMovimentacao">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Editar Evento do Histórico</h3>
                        <div class="mt-4 space-y-4">
                           <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="dataEdicao" class="block text-sm font-medium text-gray-700">Data</label>
                                    <input type="date" wire:model="dataEdicao" id="dataEdicao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @error('dataEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="tipoEdicao" class="block text-sm font-medium text-gray-700">Tipo de Evento</label>
                                    <select wire:model="tipoEdicao" id="tipoEdicao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option>Observação</option>
                                        <option>Pesagem</option>
                                        <option>Vacinação</option>
                                        <option>Medicação</option>
                                        <option>Venda</option>
                                        <option>Óbito</option>
                                    </select>
                                    @error('tipoEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="descricaoEdicao" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea wire:model="descricaoEdicao" id="descricaoEdicao" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                @error('descricaoEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="valorEdicao" class="block text-sm font-medium text-gray-700">Valor (Opcional)</label>
                                <input type="text" wire:model="valorEdicao" id="valorEdicao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('valorEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Salvar Alterações</button>
                        <button type="button" wire:click="$set('showEditModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
