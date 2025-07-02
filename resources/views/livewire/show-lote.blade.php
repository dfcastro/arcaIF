<div>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lote: <span class="text-indigo-600">{{ $lote->nome }}</span>
            </h2>
            <a href="{{ url('/lotes') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                &larr; Voltar para todos os lotes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Coluna da Esquerda: Animais no Lote --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Animais no Lote ({{ $animaisNoLote->total() }})</h3>
                    <div class="mt-4 border-t border-gray-200">
                        <ul class="divide-y divide-gray-200">
                            @forelse ($animaisNoLote as $animal)
                                <li class="py-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $animal->identificacao }}</p>
                                        <p class="text-sm text-gray-500">{{ $animal->especie->nome }} - {{ $animal->raca->nome ?? 'N/D' }}</p>
                                    </div>
                                    <button wire:click="removeAnimal({{ $animal->id }})" wire:loading.attr="disabled" class="ml-4 text-red-600 hover:text-red-800 text-sm font-medium" title="Remover do Lote">
                                        Remover
                                    </button>
                                </li>
                            @empty
                                <li class="py-4 text-center text-sm text-gray-500">
                                    Nenhum animal neste lote.
                                </li>
                            @endforelse
                        </ul>
                        @if($animaisNoLote->hasPages())
                            <div class="mt-4">
                                {{ $animaisNoLote->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Coluna da Direita: Adicionar Animais ao Lote --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Adicionar Animal ao Lote</h3>
                    
                    <div class="mt-4">
                        <input 
                            wire:model.live.debounce.300ms="termoBusca"
                            type="text" 
                            placeholder="Buscar por identificação..." 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>

                    @if (session()->has('sucesso'))
                        <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3" role="alert">
                            <p>{{ session('sucesso') }}</p>
                        </div>
                    @endif

                    <div class="mt-4 border-t border-gray-200">
                        <ul class="divide-y divide-gray-200">
                            @forelse ($animaisDisponiveis as $animal)
                                <li class="py-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $animal->identificacao }}</p>
                                        <p class="text-sm text-gray-500">{{ $animal->especie->nome }} - {{ $animal->raca->nome ?? 'N/D' }}</p>
                                    </div>
                                    <button wire:click="addAnimal({{ $animal->id }})" wire:loading.attr="disabled" class="ml-4 text-indigo-600 hover:text-indigo-800 text-sm font-medium" title="Adicionar ao Lote">
                                        Adicionar
                                    </button>
                                </li>
                            @empty
                                @if(empty($termoBusca))
                                    <li class="py-4 text-center text-sm text-gray-500">
                                        Todos os animais já estão em um lote ou não há animais disponíveis.
                                    </li>
                                @else
                                     <li class="py-4 text-center text-sm text-gray-500">
                                        Nenhum animal encontrado com a identificação "{{ $termoBusca }}".
                                    </li>
                                @endif
                            @endforelse
                        </ul>
                         @if($animaisDisponiveis->hasPages())
                            <div class="mt-4">
                                {{ $animaisDisponiveis->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
