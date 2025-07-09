<div>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ficha do Animal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Cabeçalho de Perfil do Animal --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 md:flex md:items-center md:space-x-6">
                    <div class="flex-shrink-0 mx-auto md:mx-0 w-24 h-24 flex items-center justify-center bg-if-green-100 rounded-full">
                        <i class="fas {{ $this->getIconeEspecie() }} fa-3x text-if-green-600"></i>
                    </div>
                    <div class="mt-4 md:mt-0 text-center md:text-left flex-grow">
                        <div class="flex items-center justify-center md:justify-start">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $animal->identificacao }}</h1>
                            <span @class(['ml-3 px-2 py-1 text-xs font-semibold leading-5 rounded-full', 'bg-green-100 text-green-800'=> $animal->status == 'Ativo', 'bg-yellow-100 text-yellow-800' => $animal->status == 'Vendido', 'bg-red-100 text-red-800' => $animal->status == 'Óbito'])>{{ $animal->status }}</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            <span>{{ $animal->especie->nome }}</span><span class="mx-2">&bull;</span>
                            <span>{{ $animal->raca->nome ?? 'Raça não definida' }}</span><span class="mx-2">&bull;</span>
                            <span>{{ $animal->formatted_age }}</span><span class="mx-2">&bull;</span>
                            <span>{{ $animal->sexo }}</span>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 text-center md:text-right">
                        <a href="{{ url('/animais') }}" class="text-sm text-gray-600 hover:text-if-green-600">&larr; Voltar para a lista</a>
                    </div>
                </div>
            </div>

            {{-- Conteúdo Principal da Página --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Coluna da Esquerda --}}
                <div class="lg:col-span-1 space-y-8">

                    {{-- Painel de Ações Rápidas --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Ações Rápidas</h3>
                            <div class="mt-4 grid grid-cols-1 gap-4">
                                @if($animal->sexo == 'Fêmea')
                                <x-secondary-button wire:click="abrirModalReprodutivo" class="w-full justify-center">
                                    <i class="fas fa-venus-mars mr-2"></i> Registar Evento Reprodutivo
                                </x-secondary-button>
                                @endif
                                <x-secondary-button wire:click="abrirModalProtocolo" class="w-full justify-center">
                                    <i class="fas fa-file-medical-alt mr-2"></i> Aplicar Protocolo Sanitário
                                </x-secondary-button>
                            </div>
                        </div>
                    </div>

                    {{-- Card do Ciclo Reprodutivo --}}
                    <x-animal.reproductive-cycle-card :animal="$animal" />

                    {{-- Card de Dados Cadastrais e Parentesco --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium leading-6">Detalhes e Genealogia</h3>
                            <div class="mt-4 border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Localização</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $animal->localizacao->nome ?? 'Não definida' }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Categoria</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $animal->categoria->nome ?? 'Não definida' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Pai</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">@if($animal->pai)<a href="{{ route('animais.show', $animal->pai) }}" class="text-if-green-600 hover:underline">{{ $animal->pai->identificacao }}</a>@else Não identificado @endif</dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Mãe</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">@if($animal->mae)<a href="{{ route('animais.show', $animal->mae) }}" class="text-if-green-600 hover:underline">{{ $animal->mae->identificacao }}</a>@else Não identificada @endif</dd>
                                    </div>
                                    @if($animal->observacoes)<div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Observações</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $animal->observacoes }}</dd>
                                    </div>@endif
                                </dl>
                            </div>
                        </div>
                    </div>

                    {{-- Card do Gráfico de Pesagem --}}
                    @if(!empty($pesagemChartData['labels']) && count($pesagemChartData['labels']) > 1)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" wire:ignore>
                        <div class="p-6 text-gray-900"
                            x-data="{
                                    chart: null,
                                    initChart(initialData) {
                                        if (this.chart) return;
                                        const ctx = this.$refs.canvas.getContext('2d');
                                        this.chart = new Chart(ctx, {
                                            type: 'line',
                                            data: {
                                                labels: initialData.labels,
                                                datasets: [{
                                                    label: 'Evolução do Peso (kg)',
                                                    data: initialData.data,
                                                    borderColor: 'rgba(22, 163, 74, 0.8)',
                                                    backgroundColor: 'rgba(22, 163, 74, 0.2)',
                                                    fill: true,
                                                    tension: 0.1
                                                }]
                                            },
                                            options: {
                                                animation: false,
                                                scales: { y: { beginAtZero: false } },
                                                plugins: { legend: { display: true } }
                                            }
                                        });
                                    },
                                    updateChart(newData) {
                                        if (!this.chart || !newData) return;
                                        this.chart.data.labels = newData.labels;
                                        this.chart.data.datasets[0].data = newData.data;
                                        this.chart.update();
                                    }
                                }"
                            x-init="initChart({{ json_encode($pesagemChartData) }})"
                            @update-pesagem-chart.window="updateChart($event.detail[0])">
                            <h3 class="text-lg font-medium leading-6">Evolução de Peso</h3>
                            <div class="mt-4 h-64"><canvas x-ref="canvas"></canvas></div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Coluna da Direita --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">

                            <h3 class="text-lg font-medium leading-6 text-gray-900">Adicionar Evento ao Histórico</h3>
                            <form wire:submit="salvarMovimentacao" class="mt-4 border-t border-gray-200 py-6 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div><label for="data" class="block text-sm font-medium text-gray-700">Data</label><input type="date" wire:model="data" id="data" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">@error('data')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror</div>
                                    <div><label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Evento</label><select wire:model.live="tipo" id="tipo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                            <option>Observação</option>
                                            <option>Pesagem</option>
                                            <option>Vacinação</option>
                                            <option>Medicação</option>
                                            <option>Venda</option>
                                            <option>Óbito</option>
                                        </select>@error('tipo')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror</div>
                                </div>
                                @if ($tipo === 'Pesagem')
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-2"><label for="valor" class="block text-sm font-medium text-gray-700">Peso</label><input type="number" step="any" wire:model="valor" id="valor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: 85.5"></div>
                                    <div><label for="unidade" class="block text-sm font-medium text-gray-700">Unidade</label><select wire:model="unidade" id="unidade" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                            <option>Kg</option>
                                            <option>@ (Peso Vivo)</option>
                                            <option>@ (Carcaça)</option>
                                        </select></div>
                                </div>
                                @error('valor') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                @else
                                <div><label for="valor" class="block text-sm font-medium text-gray-700">Valor (Opcional)</label><input type="text" wire:model="valor" id="valor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: nome da vacina, R$ 2.500,00">@error('valor')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror</div>
                                @endif
                                <div><label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label><textarea wire:model="descricao" id="descricao" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Descreva o evento..."></textarea>@error('descricao')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror</div>
                                <div class="text-right"><x-primary-button type="submit">Registar Evento</x-primary-button></div>
                            </form>

                            <div class="mt-6 border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Histórico e Agenda</h3>
                                <div class="mt-4">
                                    <div class="sm:hidden">
                                        <select wire:model.live="activeTab" class="block w-full rounded-md" wire:loading.attr="disabled">
                                            @foreach (['Reprodutivo', 'Agenda Sanitária', 'Todos', 'Pesagem', 'Vacinação', 'Medicação', 'Observação'] as $tab)
                                            <option>{{ $tab }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="hidden sm:block">
                                        <div class="border-b border-gray-200">
                                            <nav class="-mb-px flex space-x-8 overflow-x-auto" wire:loading.class="opacity-50 cursor-not-allowed" aria-label="Tabs">
                                                @foreach (['Reprodutivo', 'Agenda Sanitária', 'Todos', 'Pesagem', 'Vacinação', 'Medicação', 'Observação'] as $tab)
                                                <button wire:click="$set('activeTab', '{{ $tab }}')" wire:loading.attr="disabled" @class(['whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm', 'border-if-green-500 text-if-green-600'=> $activeTab === $tab, 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== $tab])>
                                                    {{ $tab }}
                                                </button>
                                                @endforeach
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 flow-root" wire:loading.class.delay="opacity-50">
                                    <ul role="list" class="-mb-8">
                                        @if($activeTab === 'Reprodutivo')
                                        @forelse ($animal->eventosReprodutivos as $evento)
                                        <li>
                                            <div class="relative pb-8">
                                                @if (!$loop->last)<span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>@endif
                                                <div class="relative flex items-start space-x-3">
                                                    <span class="h-8 w-8 rounded-full {{ $this->getCorIconeEventoReprodutivo($evento->tipo) }} flex items-center justify-center ring-8 ring-white">
                                                        {{-- >> LINHA CORRIGIDA << --}}
                                                        <i class="fas {{ $this->getIconeEventoReprodutivo($evento->tipo) }} text-white"></i>
                                                    </span>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $evento->tipo }}</p>
                                                            <p class="text-sm text-gray-500">{{ $evento->observacoes }}
                                                                @if($evento->animalRelacionado)
                                                                <span class="font-semibold">(com <a href="{{ route('animais.show', $evento->animalRelacionado) }}" class="text-if-green-600 hover:underline">{{ $evento->animalRelacionado->identificacao }}</a>)</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time>{{ $evento->data->format('d/m/Y') }}</time>
                                                            <div class="mt-1"><button wire:click="deleteEventoReprodutivo({{$evento->id}})" wire:confirm="Tem a certeza?" class="text-red-500 hover:text-red-700" title="Remover"><i class="fas fa-trash"></i></button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="py-4 text-center text-sm text-gray-500">Nenhum evento reprodutivo registado.</li>
                                        @endforelse
                                        @elseif($activeTab === 'Agenda Sanitária')
                                        @forelse ($animal->agendaSanitaria->sortBy('data_agendada') as $evento)
                                        <li>
                                            <div class="relative pb-8">
                                                @if (!$loop->last)<span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>@endif
                                                <div class="relative flex items-start space-x-3">
                                                    <div>@if($evento->status == 'Concluído')<span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white"><i class="fas fa-check text-white"></i></span>@elseif($evento->data_agendada->isPast() && !$evento->data_agendada->isToday())<span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white"><i class="fas fa-exclamation-triangle text-white"></i></span>@else<span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white"><i class="fas fa-calendar-alt text-white"></i></span>@endif</div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $evento->protocoloEvento->nome_evento }}</p>
                                                            <p class="text-sm text-gray-500">{{ $evento->protocoloEvento->instrucoes }}</p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500"><time>{{ $evento->data_agendada->format('d/m/Y') }}</time>@if($evento->status == 'Agendado')<div class="mt-1"><button wire:click="concluirEventoAgenda({{ $evento->id }})" class="text-xs font-semibold text-white bg-if-green-600 hover:bg-if-green-700 px-2 py-1 rounded">Concluir</button></div>@endif</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="py-4 text-center text-sm text-gray-500">Nenhum evento sanitário agendado.</li>
                                        @endforelse
                                        @else
                                        @forelse ($movimentacoesFiltradas as $movimentacao)
                                        <li>
                                            <div class="relative pb-8">
                                                @if (!$loop->last)<span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>@endif
                                                <div class="relative flex items-start space-x-3">
                                                    <div><span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white"><i class="fas fa-history text-white"></i></span></div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500"><span class="font-medium text-gray-900">{{ $movimentacao->tipo }}:</span> {{ $movimentacao->descricao }} @if($movimentacao->valor)<span class="font-semibold">({{ $movimentacao->valor }})</span>@endif</p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500"><time>{{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y') }}</time>
                                                            <div class="mt-1"><button wire:click="startEditing({{ $movimentacao->id }})" class="text-blue-500 hover:text-blue-700" title="Editar"><i class="fas fa-edit"></i></button><button wire:click="deleteMovimentacao({{ $movimentacao->id }})" wire:confirm="Tem a certeza?" class="ml-2 text-red-500 hover:text-red-700" title="Remover"><i class="fas fa-trash"></i></button></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="py-4 text-center text-sm text-gray-500">Nenhum evento do tipo '{{$activeTab}}' encontrado.</li>
                                        @endforelse
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAIS --}}
    @if($showAplicarProtocoloModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showAplicarProtocoloModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Aplicar Protocolo Sanitário</h3>
                    <div class="mt-4 space-y-3">
                        <div>
                            <label for="protocolo" class="block text-sm font-medium text-gray-700">Selecione o protocolo:</label>
                            <select wire:model="protocoloSelecionado" id="protocolo" class="mt-1 block w-full rounded-md">
                                <option value="">Selecione...</option>
                                @foreach($protocolosDisponiveis as $protocolo)
                                <option value="{{ $protocolo->id }}" @if(in_array($protocolo->id, $protocolosAplicadosIds)) disabled @endif>
                                    {{ $protocolo->nome }}
                                    @if(in_array($protocolo->id, $protocolosAplicadosIds)) (Já aplicado) @endif
                                </option>
                                @endforeach
                            </select>
                            @error('protocoloSelecionado')<span class="text-red-500 text-xs">{{$message}}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <x-primary-button wire:click="aplicarProtocolo">Aplicar</x-primary-button>
                    <x-secondary-button wire:click="$set('showAplicarProtocoloModal', false)" class="sm:mr-3 mt-3 sm:mt-0">Cancelar</x-secondary-button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showEventoReprodutivoModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showEventoReprodutivoModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="salvarEventoReprodutivo">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900">Registar Evento Reprodutivo</h3>
                        <div class="mt-4 space-y-4">
                            <div><label class="block text-sm">Tipo</label>
                                <select wire:model.live="tipo_evento_reprodutivo" class="mt-1 block w-full rounded-md text-sm">
                                    <option>Cobrição</option>
                                    <option>Inseminação</option>
                                    <option>Diagnóstico de Gestação</option>
                                    <option>Parto</option>
                                    <option>Desmame</option>
                                    <option>Aborto</option>
                                </select>
                            </div>
                            <div><label class="block text-sm">Data</label><input type="date" wire:model="data_evento_reprodutivo" class="mt-1 block w-full rounded-md text-sm">@error('data_evento_reprodutivo')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                            @if($tipo_evento_reprodutivo == 'Cobrição' || $tipo_evento_reprodutivo == 'Inseminação')
                            <div><label class="block text-sm">Macho Utilizado</label><select wire:model="macho_relacionado_id" class="mt-1 block w-full rounded-md text-sm">
                                    <option value="">Selecione...</option>@foreach($machosDisponiveis as $macho)<option value="{{$macho->id}}">{{$macho->identificacao}}</option>@endforeach
                                </select></div>
                            @endif
                            <div><label class="block text-sm">Observações</label><textarea wire:model="observacoes_reprodutivas" rows="2" class="mt-1 block w-full rounded-md text-sm"></textarea></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button type="submit">Registar</x-primary-button>
                        <x-secondary-button wire:click="$set('showEventoReprodutivoModal', false)" class="sm:mr-3 mt-3 sm:mt-0">Cancelar</x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if ($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="updateMovimentacao">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Editar Evento do Histórico</h3>
                        <div class="mt-4 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div><label for="dataEdicao" class="block text-sm font-medium text-gray-700">Data</label><input type="date" wire:model="dataEdicao" id="dataEdicao" class="mt-1 block w-full rounded-md">@error('dataEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                                <div><label for="tipoEdicao" class="block text-sm font-medium text-gray-700">Tipo</label><select wire:model.live="tipoEdicao" id="tipoEdicao" class="mt-1 block w-full rounded-md">
                                        <option>Observação</option>
                                        <option>Pesagem</option>
                                        <option>Vacinação</option>
                                        <option>Medicação</option>
                                        <option>Venda</option>
                                        <option>Óbito</option>
                                    </select>@error('tipoEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                            </div>
                            @if ($tipoEdicao === 'Pesagem')
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2"><label for="valorEdicao" class="block text-sm">Peso</label><input type="number" step="any" wire:model="valorEdicao" id="valorEdicao" class="mt-1 block w-full rounded-md"></div>
                                <div><label for="unidadeEdicao" class="block text-sm">Unidade</label><select wire:model="unidadeEdicao" id="unidadeEdicao" class="mt-1 block w-full rounded-md">
                                        <option>Kg</option>
                                        <option>@ (Peso Vivo)</option>
                                        <option>@ (Carcaça)</option>
                                    </select></div>
                            </div>
                            @error('valorEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            @else
                            <div><label for="valorEdicao" class="block text-sm">Valor</label><input type="text" wire:model="valorEdicao" id="valorEdicao" class="mt-1 block w-full rounded-md">@error('valorEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                            @endif
                            <div><label for="descricaoEdicao" class="block text-sm">Descrição</label><textarea wire:model="descricaoEdicao" id="descricaoEdicao" rows="2" class="mt-1 block w-full rounded-md"></textarea>@error('descricaoEdicao') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror</div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button type="submit">Salvar Alterações</x-primary-button>
                        <x-secondary-button type="button" wire:click="$set('showEditModal', false)">Cancelar</x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>