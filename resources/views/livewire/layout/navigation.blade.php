<div> {{-- <<-- ADICIONADO ESTE DIV DE ABERTURA --}}
    <?php
    // ARQUIVO: resources/views/livewire/layout/navigation.blade.php

    use App\Livewire\Actions\Logout;
    use Livewire\Volt\Component;

    new class extends Component {
        /**
         * Log the current user out of the application.
         */
        public function logout(Logout $logout): void
        {
            $logout();
            $this->redirect('/', navigate: true);
        }
    }; ?>

    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" wire:navigate>
                            <x-application-logo class="block h-20 w-auto fill-current text-gray-800" />
                        </a>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            <i class="fas fa-chart-pie mr-2"></i>
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="url('/animais')" :active="request()->is('animais*')" wire:navigate>
                            <i class="fas fa-paw mr-2"></i>
                            {{ __('Animais') }}
                        </x-nav-link>

                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->is('especies*') || request()->is('racas*') || request()->is('localizacoes*')) ? 'border-if-green-500 text-if-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                        <div>
                                            <i class="fas fa-edit mr-2"></i>
                                            {{ __('Cadastros') }}
                                        </div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="url('/especies')" wire:navigate>
                                        <i class="fas fa-book-open w-5 mr-2"></i>
                                        {{ __('Espécies') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="url('/racas')" wire:navigate>
                                        <i class="fas fa-dna w-5 mr-2"></i>
                                        {{ __('Raças') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="url('/localizacoes')" wire:navigate>
                                        <i class="fas fa-map-marker-alt w-5 mr-2"></i>
                                        {{ __('Localizações') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <x-nav-link :href="url('/lotes')" :active="request()->is('lotes*')" wire:navigate>
                            <i class="fas fa-box-open mr-2"></i>
                            {{ __('Lotes') }}
                        </x-nav-link>

                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->is('categorias*') || request()->is('ingredientes*') || request()->is('formulador*') || request()->is('formulas*')) ? 'border-if-green-500 text-if-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                        <div>
                                            <i class="fas fa-leaf mr-2"></i>
                                            {{ __('Nutrição') }}
                                        </div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="url('/categorias')" wire:navigate><i class="fas fa-tags w-5 mr-2"></i>{{ __('Categorias de Animais') }}</x-dropdown-link>
                                    <x-dropdown-link :href="url('/ingredientes')" wire:navigate><i class="fas fa-wheat-alt w-5 mr-2"></i>{{ __('Ingredientes') }}</x-dropdown-link>
                                    <x-dropdown-link :href="url('/formulador')" wire:navigate><i class="fas fa-calculator w-5 mr-2"></i>{{ __('Formulador') }}</x-dropdown-link>
                                    <x-dropdown-link :href="url('/formulas')" wire:navigate><i class="fas fa-archive w-5 mr-2"></i>{{ __('Fórmulas Salvas') }}</x-dropdown-link>
                                    <x-dropdown-link :href="url('/previsao-consumo')" :active="request()->is('previsao-consumo*')" wire:navigate>
                                        <i class="fas fa-chart-line mr-2"></i>
                                        {{ __('Previsão de Consumo') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->is('protocolos*') || request()->is('agenda*')) ? 'border-if-green-500 text-if-green-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                        <div><i class="fas fa-heartbeat mr-2"></i>{{ __('Sanidade') }}</div>
                                        <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg></div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('agenda.sanitaria')" wire:navigate>
                                        <i class="fas fa-calendar-alt w-5 mr-2"></i>
                                        {{ __('Calendário Sanitário') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="url('/protocolos')" wire:navigate>
                                        <i class="fas fa-file-medical-alt w-5 mr-2"></i>
                                        {{ __('Protocolos') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <x-nav-link :href="route('relatorios')" :active="request()->routeIs('relatorios')" wire:navigate>
                            <i class="fas fa-file-alt mr-2"></i>
                            {{ __('Relatórios') }}
                        </x-nav-link>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div class="flex items-center">
                                    <i class="fas fa-user-circle fa-lg mr-2 text-gray-400"></i>
                                    <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                                </div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/animais')" :active="request()->is('animais*')" wire:navigate>{{ __('Animais') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/especies')" :active="request()->is('especies*')" wire:navigate>{{ __('Espécies') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/racas')" :active="request()->is('racas*')" wire:navigate>{{ __('Raças') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/lotes')" :active="request()->is('lotes*')" wire:navigate>{{ __('Lotes') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/localizacoes')" :active="request()->is('localizacoes*')" wire:navigate>{{ __('Localizações') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/categorias')" :active="request()->is('categorias*')" wire:navigate>{{ __('Categorias') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/ingredientes')" :active="request()->is('ingredientes*')" wire:navigate>{{ __('Ingredientes') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/formulador')" :active="request()->is('formulador*')" wire:navigate>{{ __('Formulador') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="url('/formulas')" :active="request()->is('formulas*')" wire:navigate>{{ __('Fórmulas Salvas') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('relatorios')" :active="request()->routeIs('relatorios')" wire:navigate>{{ __('Relatórios') }}</x-responsive-nav-link>
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>{{ __('Profile') }}</x-responsive-nav-link>
                    <button wire:click="logout" class="w-full text-start"><x-responsive-nav-link>{{ __('Log Out') }}</x-responsive-nav-link></button>
                </div>
            </div>
        </div>
    </nav>

</div> {{-- <<-- ADICIONADO ESTE DIV DE FECHO --}}