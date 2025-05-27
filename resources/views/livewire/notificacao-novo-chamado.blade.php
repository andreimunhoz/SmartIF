@if ($mostrarNotificacao)
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 5000)" 
        x-show="show"
        class="fixed top-0 left-0 right-0 bg-green-500 text-white text-center p-3 z-50"
        role="alert"
        wire:keydown.escape="fecharNotificacao"
    >
        {{ $mensagem }}
        <button class="ml-4 font-bold" wire:click="fecharNotificacao" aria-label="Fechar notificação">&times;</button>
    </div>
@endif

<script>
    window.addEventListener('ocultarNotificacao', event => {
        setTimeout(() => {
            Livewire.emit('fecharNotificacao');
        }, event.detail.tempo);
    });
</script>
