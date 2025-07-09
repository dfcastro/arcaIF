{{-- ARQUIVO: resources/views/components/animal/reproductive-cycle-card.blade.php --}}

@props(['animal'])

@php
    $cycle = $animal->reproductive_cycle;
@endphp

@if ($animal->sexo === 'Fêmea' && $cycle)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Ciclo Reprodutivo</h3>
            
            <div class="mt-4 text-center bg-if-green-50 text-if-green-800 rounded-lg p-3">
                <p class="text-sm font-medium">Status Atual</p>
                <p class="text-xl font-bold">{{ $cycle->status }}</p>
                @if(isset($cycle->info))
                    <p class="text-xs">{{ $cycle->info }}</p>
                @endif
            </div>

            @if (!empty($cycle->dates))
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <h4 class="text-sm font-medium text-gray-500">Datas Importantes</h4>
                    <ul class="mt-2 space-y-2">
                        @foreach ($cycle->dates as $label => $date)
                        <li class="flex items-center text-sm">
                            <i class="fas fa-calendar-alt text-gray-400 mr-3"></i>
                            <span class="flex-grow font-medium">{{ $label }}</span>
                            <span class="font-semibold text-gray-700">{{ $date->format('d/m/Y') }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endif