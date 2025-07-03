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
                case 'success': return 'bg-green-500 text-white';
                case 'error': return 'bg-red-500 text-white';
                case 'warning': return 'bg-yellow-500 text-black';
                default: return 'bg-blue-500 text-white';
            }
        },
        showNotification(event) {
            // CORREÇÃO: Acessamos o primeiro item do array 'detail'
            const payload = event.detail[0] || {};

            this.type = payload.type || 'success';
            this.message = payload.message || 'Operação realizada com sucesso!';
            this.show = true;
            
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.show = false, 4000);
        }
    }"
    x-show="show"
    x-on:toast-notification.window="showNotification($event)"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-5 right-5 z-50 w-full max-w-xs"
    style="display: none;"
>
    <div class="rounded-md shadow-lg" :class="colorClass">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas" :class="iconClass" style="font-size: 1.25rem;"></i>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium" x-text="message"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="show = false" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2" :class="colorClass">
                        <span class="sr-only">Fechar</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>