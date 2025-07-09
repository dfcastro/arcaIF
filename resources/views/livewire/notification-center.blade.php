<div class="relative" x-data="{ open: false }" @click.away="open = false">

    {{-- Ícone do Sino --}}
    <button @click="open = !open" class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
        <i class="fas fa-bell fa-lg"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Painel Dropdown das Notificações --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
         style="display: none;">
        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
            <div class="flex justify-between items-center px-4 py-2 border-b">
                <h3 class="text-sm font-semibold text-gray-700">Notificações</h3>
                @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-if-green-600 hover:underline">Marcar todas como lidas</button>
                @endif
            </div>

            <div class="max-h-96 overflow-y-auto">
                @forelse ($notifications as $notification)
                    <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" 
                       class="flex items-start px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 @if(!$notification->read_at) bg-if-green-50 @endif">
                        <i class="{{ $notification->data['icone'] ?? 'fas fa-info-circle' }} mt-1 mr-3 text-if-green-500"></i>
                        <div class="flex-1">
                            <p class="whitespace-normal">{{ $notification->data['mensagem'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-8 text-center">
                        <i class="fas fa-check-circle text-gray-300 fa-2x"></i>
                        <p class="mt-2 text-sm text-gray-500">Nenhuma notificação por aqui.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>