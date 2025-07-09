<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-20 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        <i class="fas fa-chart-pie w-5 text-center mr-1"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="url('/animais')" :active="request()->is('animais*')" wire:navigate>
                        <i class="fas fa-paw w-5 text-center mr-1"></i>
                        {{ __('Animais') }}
                    </x-nav-link>

                    <x-nav-link :href="url('/lotes')" :active="request()->is('lotes*')" wire:navigate>
                        <i class="fas fa-box-open w-5 text-center mr-1"></i>
                        {{ __('Lotes') }}
                    </x-nav-link>

                    {{-- Dropdown de Cadastros --}}
                    @can('access-admin-area')
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is(['especies*', 'racas*', 'localizacoes*', 'categorias*']) ? 'border-if-green-400' : 'border-transparent' }} text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div><i class="fas fa-edit w-5 text-center mr-1"></i>Cadastros</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="url('/especies')" wire:navigate><i class="fas fa-book-open w-5 mr-2"></i>{{ __('Espécies') }}</x-dropdown-link>
                                <x-dropdown-link :href="url('/racas')" wire:navigate><i class="fas fa-dna w-5 mr-2"></i>{{ __('Raças') }}</x-dropdown-link>
                                <x-dropdown-link :href="url('/categorias')" wire:navigate><i class="fas fa-tags w-5 mr-2"></i>{{ __('Categorias de Animais') }}</x-dropdown-link>
                                <x-dropdown-link :href="url('/localizacoes')" wire:navigate><i class="fas fa-map-marker-alt w-5 mr-2"></i>{{ __('Localizações') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcan

                    {{-- Dropdown de Nutrição --}}
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is(['ingredientes*', 'formulador*', 'formulas*', 'previsao-consumo*']) ? 'border-if-green-400' : 'border-transparent' }} text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div><i class="fas fa-leaf w-5 text-center mr-1"></i>Nutrição</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="url('/ingredientes')" wire:navigate><i class="fas fa-wheat-alt w-5 mr-2"></i>{{ __('Ingredientes') }}</x-dropdown-link>
                                <x-dropdown-link :href="url('/formulador')" wire:navigate><i class="fas fa-calculator w-5 mr-2"></i>{{ __('Formulador') }}</x-dropdown-link>
                                <x-dropdown-link :href="url('/formulas')" wire:navigate><i class="fas fa-archive w-5 mr-2"></i>{{ __('Fórmulas Salvas') }}</x-dropdown-link>
                                <x-dropdown-link :href="url('/previsao-consumo')" wire:navigate><i class="fas fa-chart-line w-5 mr-2"></i>{{ __('Previsão de Consumo') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Dropdown de Sanidade --}}
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is(['protocolos*', 'agenda*']) ? 'border-if-green-400' : 'border-transparent' }} text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div><i class="fas fa-heartbeat w-5 text-center mr-1"></i>Sanidade</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('agenda.sanitaria')" wire:navigate><i class="fas fa-calendar-alt w-5 mr-2"></i>{{ __('Calendário Sanitário') }}</x-dropdown-link>
                                <x-dropdown-link :href="url('/protocolos')" wire:navigate><i class="fas fa-file-medical-alt w-5 mr-2"></i>{{ __('Protocolos') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
               

                <div class="ms-4">
                    <livewire:notification-center />
                </div>

                <div class="ms-4">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <div x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name.split(' ')[0]"></div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @can('access-admin-area')
                            <div class="px-4 py-2 text-xs text-gray-400">Admin</div>
                            <x-dropdown-link :href="url('/relatorios')" wire:navigate><i class="fas fa-file-alt w-5 mr-2"></i>{{ __('Relatórios') }}</x-dropdown-link>
                            <x-dropdown-link :href="url('/utilizadores')" wire:navigate><i class="fas fa-users-cog w-5 mr-2"></i>{{ __('Utilizadores') }}</x-dropdown-link>
                            <hr>
                            @endcan
                            <x-dropdown-link :href="route('profile')" wire:navigate>{{ __('Meu Perfil') }}</x-dropdown-link>
                            <button wire:click="logout" class="w-full text-start"><x-dropdown-link>{{ __('Sair') }}</x-dropdown-link></button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <div class="me-3"><livewire:notification-center /></div>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/animais')" :active="request()->is('animais*')" wire:navigate>{{ __('Animais') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/lotes')" :active="request()->is('lotes*')" wire:navigate>{{ __('Lotes') }}</x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">Cadastros</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="url('/especies')" wire:navigate>{{ __('Espécies') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/racas')" wire:navigate>{{ __('Raças') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/categorias')" wire:navigate>{{ __('Categorias') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/localizacoes')" wire:navigate>{{ __('Localizações') }}</x-responsive-nav-link>
            </div>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">Nutrição</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="url('/ingredientes')" wire:navigate>{{ __('Ingredientes') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/formulador')" wire:navigate>{{ __('Formulador') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/formulas')" wire:navigate>{{ __('Fórmulas Salvas') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/previsao-consumo')" wire:navigate>{{ __('Previsão de Consumo') }}</x-responsive-nav-link>
            </div>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">Sanidade</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('agenda.sanitaria')" wire:navigate>{{ __('Calendário Sanitário') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/protocolos')" wire:navigate>{{ __('Protocolos') }}</x-responsive-nav-link>
            </div>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                @can('access-admin-area')
                <x-responsive-nav-link :href="url('/relatorios')" wire:navigate>{{ __('Relatórios') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/utilizadores')" wire:navigate>{{ __('Utilizadores') }}</x-responsive-nav-link>
                @endcan
                <x-responsive-nav-link :href="route('profile')" wire:navigate>{{ __('Meu Perfil') }}</x-responsive-nav-link>
                <button wire:click="logout" class="w-full text-start"><x-responsive-nav-link>{{ __('Sair') }}</x-responsive-nav-link></button>
            </div>
        </div>
    </div>
</nav>