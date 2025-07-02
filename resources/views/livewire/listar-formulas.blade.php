<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fórmulas de Ração Salvas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="sm:flex sm:items-center sm:justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Catálogo de Fórmulas</h3>
                            <p class="mt-1 text-sm text-gray-500">Visualize e gira as dietas que você criou no formulador.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                             <a href="{{ url('/formulador') }}" class="inline-flex items-center px-4 py-2 bg-if-green border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-if-green-600 focus:bg-if-green-700 active:bg-if-green-700 focus:outline-none focus:ring-2 focus:ring-if-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fa-solid fa-plus mr-2 -ml-1"></i>
                                Nova Fórmula
                            </a>
                        </div>
                    </div>

                    @if (session()->has('sucesso'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert"><p>{{ session('sucesso') }}</p></div>
                    @endif

                    <div class="border-t border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome da Fórmula</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Espécie</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Custo por kg</th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($formulas as $formula)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $formula->nome_formula }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $formula->especie->nome }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">R$ {{ number_format($formula->custo_por_kg, 4, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                                {{-- Futuramente, um botão de editar levaria para o formulador com os dados carregados --}}
                                                <button wire:click="confirmarDelecao({{ $formula->id }})" class="text-red-600 hover:text-red-900" title="Deletar"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-6 py-12 text-center">
                                            <div class="text-center">
                                                <i class="fas fa-archive fa-3x text-gray-300"></i>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma fórmula salva</h3>
                                                <p class="mt-1 text-sm text-gray-500">Vá para o formulador para criar a sua primeira dieta.</p>
                                            </div>
                                        </td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($formulas->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200">{{ $formulas->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modal de Confirmação de Exclusão --}}
    @if ($modalDelecaoAberto)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"><i class="fas fa-exclamation-triangle text-red-600"></i></div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Excluir Fórmula</h3>
                            <div class="mt-2"><p class="text-sm text-gray-500">Tem a certeza de que deseja excluir esta fórmula? Os animais que a utilizam ficarão sem uma dieta definida.</p></div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <x-danger-button wire:click="deletar()" class="sm:ml-3">Confirmar Exclusão</x-danger-button>
                    <x-secondary-button wire:click="$set('modalDelecaoAberto', false)" class="sm:mt-0 mt-3">Cancelar</x-secondary-button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>