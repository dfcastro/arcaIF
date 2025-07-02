<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Protocolos Sanitários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="sm:flex sm:items-center sm:justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Modelos de Protocolos</h3>
                            <p class="mt-1 text-sm text-gray-500">Crie e gira os modelos de tratamentos e vacinações.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                             <x-primary-button wire:click="abrirModal()">
                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                Novo Protocolo
                            </x-primary-button>
                        </div>
                    </div>

                    @if (session()->has('sucesso'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert"><p>{{ session('sucesso') }}</p></div>
                    @endif

                    <div class="border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome do Protocolo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Espécie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nº de Etapas</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($protocolos as $protocolo)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $protocolo->nome }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $protocolo->especie->nome }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $protocolo->eventos_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <button wire:click="editar({{ $protocolo->id }})" class="text-if-green-600 hover:text-if-green-900" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                                            <button wire:click="confirmarDelecao({{ $protocolo->id }})" class="text-red-600 hover:text-red-900" title="Deletar"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-center">
                                            <i class="fas fa-file-medical-alt fa-3x text-gray-300"></i>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum protocolo encontrado</h3>
                                            <p class="mt-1 text-sm text-gray-500">Comece por cadastrar o seu primeiro protocolo sanitário.</p>
                                        </div>
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($protocolos->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200">{{ $protocolos->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Adicionar/Editar Protocolo --}}
    @if ($modalAberto)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <form wire:submit.prevent="salvar">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                         <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-if-green-100 sm:mx-0 sm:h-10 sm:w-10"><i class="fas fa-file-medical-alt text-if-green-600"></i></div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $protocoloId ? 'Editar Protocolo' : 'Novo Protocolo Sanitário' }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Preencha os dados gerais e adicione as etapas do tratamento.</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 border-t pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2"><label class="block text-sm font-medium">Nome do Protocolo</label><input type="text" wire:model="nome" class="mt-1 block w-full rounded-md">@error('nome')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                            <div><label class="block text-sm font-medium">Espécie</label><select wire:model="especie_id" class="mt-1 block w-full rounded-md"><option value="">Selecione...</option>@foreach($todasEspecies as $especie)<option value="{{$especie->id}}">{{$especie->nome}}</option>@endforeach</select>@error('especie_id')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                            <div class="md:col-span-2"><label class="block text-sm font-medium">Descrição</label><textarea wire:model="descricao" rows="2" class="mt-1 block w-full rounded-md"></textarea></div>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <h4 class="text-md font-medium text-gray-800">Etapas do Protocolo</h4>
                            @error('eventos')<span class="text-red-500 text-sm font-semibold">{{$message}}</span>@enderror
                            
                            @foreach($eventos as $index => $evento)
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center mt-3 p-3 bg-gray-50 rounded-md" wire:key="evento-{{ $index }}">
                                <div class="col-span-12 md:col-span-4"><label class="text-sm">Nome do Evento</label><input type="text" wire:model="eventos.{{$index}}.nome_evento" class="mt-1 block w-full rounded-md">@error('eventos.'.$index.'.nome_evento')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                                <div class="col-span-6 md:col-span-2"><label class="text-sm">Tipo</label><select wire:model="eventos.{{$index}}.tipo" class="mt-1 block w-full rounded-md"><option value="">Selecione</option>@foreach($tiposDeEvento as $tipo)<option value="{{$tipo}}">{{$tipo}}</option>@endforeach</select>@error('eventos.'.$index.'.tipo')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                                <div class="col-span-6 md:col-span-2"><label class="text-sm">Dias Após Início</label><input type="number" wire:model="eventos.{{$index}}.dias_apos_inicio" class="mt-1 block w-full rounded-md">@error('eventos.'.$index.'.dias_apos_inicio')<span class="text-red-500 text-xs">{{$message}}</span>@enderror</div>
                                <div class="col-span-11 md:col-span-3"><label class="text-sm">Instruções</label><input type="text" wire:model="eventos.{{$index}}.instrucoes" class="mt-1 block w-full rounded-md"></div>
                                <div class="col-span-1 pt-5 text-right"><button wire:click.prevent="removerEvento({{$index}})" class="text-red-500 hover:text-red-700" title="Remover Etapa"><i class="fas fa-trash-alt"></i></button></div>
                            </div>
                            @endforeach

                            <button wire:click.prevent="adicionarEvento" type="button" class="mt-4 text-sm text-if-green-600 font-semibold hover:underline">
                                <i class="fas fa-plus mr-1"></i> Adicionar Etapa
                            </button>
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
    <div class="fixed inset-0 z-50 overflow-y-auto">
        {{-- ... (código do modal de deleção, igual aos outros) ... --}}
    </div>
    @endif
</div>