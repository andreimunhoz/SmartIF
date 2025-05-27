<div>
    @livewire('alerta-chamado-novo')

    <script>
        Livewire.on('novo-chamado-alerta', () => {
            alert('🚨 Novo chamado aberto!');
        });
    </script>
</div>
