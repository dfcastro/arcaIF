<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agenda e Calendário Sanitário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session()->has('sucesso'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                    <p>{{ session('sucesso') }}</p>
                </div>
            @endif

            {{-- Painel de Ações Imediatas --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                {{-- Coluna: Atrasados --}}
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="font-bold text-lg text-red-700 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Atrasados ({{ count($eventosAtrasados) }})
                    </h3>
                    <div class="mt-3 space-y-3 max-h-60 overflow-y-auto">
                        @forelse($eventosAtrasados as $evento)
                            <x-sanidade.evento-card :evento="$evento" />
                        @empty
                            <p class="text-sm text-gray-500">Nenhum evento atrasado.</p>
                        @endforelse
                    </div>
                </div>
                {{-- Coluna: Hoje --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-bold text-lg text-blue-700 flex items-center">
                        <i class="fas fa-calendar-day mr-2"></i> Para Hoje ({{ count($eventosHoje) }})
                    </h3>
                    <div class="mt-3 space-y-3 max-h-60 overflow-y-auto">
                        @forelse($eventosHoje as $evento)
                            <x-sanidade.evento-card :evento="$evento" />
                        @empty
                            <p class="text-sm text-gray-500">Nenhum evento para hoje.</p>
                        @endforelse
                    </div>
                </div>
                {{-- Coluna: Próximos 7 Dias --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="font-bold text-lg text-yellow-700 flex items-center">
                        <i class="fas fa-calendar-week mr-2"></i> Próximos 7 Dias ({{ count($eventosProximos) }})
                    </h3>
                     <div class="mt-3 space-y-3 max-h-60 overflow-y-auto">
                        @forelse($eventosProximos as $evento)
                            <x-sanidade.evento-card :evento="$evento" />
                        @empty
                            <p class="text-sm text-gray-500">Nenhum evento nos próximos 7 dias.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Calendário Mensal Visual --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Cabeçalho do Calendário com Navegação --}}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            {{ $dataAtual->translatedFormat('F Y') }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            <x-secondary-button wire:click="irParaMesAnterior"><i class="fas fa-chevron-left"></i></x-secondary-button>
                            <x-secondary-button wire:click="irParaHoje">Hoje</x-secondary-button>
                            <x-secondary-button wire:click="irParaMesSeguinte"><i class="fas fa-chevron-right"></i></x-secondary-button>
                        </div>
                    </div>

                    {{-- Grelha do Calendário --}}
                    <div class="grid grid-cols-7 gap-px border-t border-l border-gray-200 bg-gray-200">
                        {{-- Cabeçalho dos Dias da Semana --}}
                        @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $dia)
                            <div class="text-center bg-gray-50 py-2 text-xs font-medium text-gray-500 uppercase">{{ $dia }}</div>
                        @endforeach

                        {{-- Espaços em branco para o início do mês --}}
                        @if($diasDoMes[0]->dayOfWeek > 0)
                            @for ($i = 0; $i < $diasDoMes[0]->dayOfWeek; $i++)
                                <div class="bg-gray-50 h-32"></div>
                            @endfor
                        @endif

                        {{-- Dias do Mês --}}
                        @foreach($diasDoMes as $dia)
                            <div class="relative bg-white p-2 h-32 overflow-y-auto">
                                <time datetime="{{ $dia->toDateString() }}" 
                                      @class([
                                        'font-bold' => $dia->isToday(),
                                        'text-if-green-600' => $dia->isToday(),
                                      ])>
                                    {{ $dia->day }}
                                </time>
                                
                                {{-- Eventos do Dia --}}
                                @if(isset($eventosDoMes[$dia->day]))
                                    <div class="mt-1 space-y-1">
                                        @foreach($eventosDoMes[$dia->day] as $evento)
                                            <button wire:click="abrirModalEvento({{ $evento->id }})" 
                                                    class="w-full text-left bg-blue-100 text-blue-800 text-xs font-semibold p-1 rounded-md overflow-hidden truncate hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                    title="{{ $evento->protocoloEvento->nome_evento }} - Animal: {{ $evento->animal->identificacao }}">
                                                {{ $evento->protocoloEvento->nome_evento }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal de Detalhes do Evento --}}
    @if ($modalAberto && $eventoSelecionado)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="fecharModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-if-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-calendar-check text-if-green-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Detalhes do Evento Agendado
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Data Agendada: <span class="font-bold">{{ $eventoSelecionado->data_agendada->format('d/m/Y') }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4 border-t pt-4">
                        <dl>
                            <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Animal</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a href="{{ route('animais.show', $eventoSelecionado->animal) }}" class="text-if-green-600 hover:underline font-bold">
                                        {{ $eventoSelecionado->animal->identificacao }}
                                    </a>
                                </dd>
                            </div>
                            <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Evento</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $eventoSelecionado->protocoloEvento->nome_evento }}</dd>
                            </div>
                            <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $eventoSelecionado->protocoloEvento->tipo }}</dd>
                            </div>
                            @if($eventoSelecionado->protocoloEvento->instrucoes)
                            <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Instruções</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $eventoSelecionado->protocoloEvento->instrucoes }}</dd>
                            </div>
                            @endif
                            <div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $eventoSelecionado->status == 'Agendado' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $eventoSelecionado->status }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if($eventoSelecionado->status == 'Agendado')
                    <x-primary-button wire:click="concluirEventoAgenda({{ $eventoSelecionado->id }})" wire:loading.attr="disabled">
                        <i class="fas fa-check mr-2"></i> Marcar como Concluído
                    </x-primary-button>
                    @endif
                    <x-secondary-button wire:click="fecharModal()" class="sm:mr-3 mt-3 sm:mt-0">
                        Fechar
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>