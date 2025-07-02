<!-- ARQUIVO: resources/views/profile.blade.php -->
<!-- INSTRUÇÃO: Substitua o conteúdo deste ficheiro por este código. -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meu Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- CORREÇÃO: Removido o 'pages.' do caminho do componente --}}
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- CORREÇÃO: Removido o 'pages.' do caminho do componente --}}
                    <livewire:profile.update-password-form />
                </div>
            </div>
            
            {{-- Apenas utilizadores que não são administradores podem apagar a própria conta --}}
            @unless(auth()->user()->role === 'administrador')
                <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg">
                    <div class="max-w-xl">
                        {{-- CORREÇÃO: Removido o 'pages.' do caminho do componente --}}
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            @endunless
        </div>
    </div>
</x-app-layout>
