{{-- ARQUIVO: resources/views/components/toast.blade.php --}}
@props(['type' => 'success', 'message' => ''])

<div
    x-data="{
        show: false,
        type: '{{ $type }}',
        message: '{{ $message }}',
        timeout: null,
        get iconClass() {
            switch(this.type) {
                case 'success': return 'fa-check-circle';
                case 'error': return 'fa-times-circle';
                case 'warning': return 'fa-exclamation-triangle';
                default: return 'fa-info-circle';
            }
        },
        get colorClass() {
             switch(this.type) {
                case 'success': return 'bg-green-500';
                case 'error': return 'bg-red-500';
                case 'warning': return 'bg-yellow-500';
                default: return 'bg-blue-500';
            }
        }
    }"
    x-init="
        $watch('show', value => {
            if (value) {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => show = false, 3000);
            }
        });
        window.addEventListener('toast-notification', event => {
            this.type = event.detail.type || 'success';
            this.message = event.detail.message || 'Operação realizada com sucesso!';
            this.show = true;
        });
    "
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-5 right-5 z-50 max-w-xs w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
    style="display: none;"
>
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas" :class="[iconClass, colorClass.replace('bg-', 'text-')]" style="font-size: 1.25rem;"></i>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900" x-text="message"></p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Fechar</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>