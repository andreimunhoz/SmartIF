<div
    x-data="{ show: false }"
    x-on:novo-chamado-alerta.window="show = true; setTimeout(() => show = false, 4000)"
    x-show="show"
    x-transition
    class="bg-yellow-300 text-black px-4 py-2 rounded shadow mt-4 mx-4"
>
    🚨 Novo chamado recebido!
</div>
