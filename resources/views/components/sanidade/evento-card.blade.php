{{-- O card inteiro agora é um botão que abre o modal --}}
<button wire:click="abrirModalEvento({{ $evento->id }})" class="w-full text-left bg-white p-3 rounded-md shadow-sm border-l-4 
    @if($evento->data_agendada->isPast() && !$evento->data_agendada->isToday()) border-red-500 @else border-blue-500 @endif
    hover:shadow-lg hover:border-if-green-500 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-if-green-500">
    
    <div class="flex justify-between items-start">
        <div>
            <p class="font-bold text-sm text-gray-800">{{ $evento->protocoloEvento->nome_evento }}</p>
            <span class="text-xs text-if-green-600 hover:underline">
                Animal: {{ $evento->animal->identificacao }}
            </span>
        </div>
        <p class="text-xs font-semibold text-gray-600">{{ $evento->data_agendada->format('d/m/Y') }}</p>
    </div>
    
    @if($evento->protocoloEvento->instrucoes)
        <p class="text-xs text-gray-500 mt-1 italic overflow-hidden truncate">
            Instruções: {{ $evento->protocoloEvento->instrucoes }}
        </p>
    @endif
</button>