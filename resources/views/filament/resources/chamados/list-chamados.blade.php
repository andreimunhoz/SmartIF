<div>
    @if($newChamadoAlert)
        <div
            id="new-chamado-alert"
            class="bg-blue-500 text-white p-4 rounded shadow mb-4"
            role="alert"
        >
            📢 Novo chamado recebido!
        </div>
    @endif

    {{ $this->table }}

</div>

<script>
    window.addEventListener('hide-chamado-alert', event => {
        setTimeout(() => {
            const alert = document.getElementById('new-chamado-alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, event.detail.timeout);
    });
</script>
