@props([
    'sortable' => false,
    'direction' => null,
])

<th {{ $attributes->merge(['class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'])->except('wire:click') }}>
    @if ($sortable)
        <button {{ $attributes->only('wire:click') }} class="flex items-center space-x-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider group focus:outline-none focus:underline">
            <span>{{ $slot }}</span>
            <span class="relative flex items-center">
                @if ($direction === 'asc')
                    <i class="fas fa-sort-up text-gray-600"></i>
                @elseif ($direction === 'desc')
                    <i class="fas fa-sort-down text-gray-600"></i>
                @else
                    <i class="fas fa-sort text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                @endif
            </span>
        </button>
    @else
        <span>{{ $slot }}</span>
    @endif
</th>