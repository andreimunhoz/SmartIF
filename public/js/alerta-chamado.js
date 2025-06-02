document.addEventListener("DOMContentLoaded", () => {
    console.log("Script carregado!");

    function obterMaiorIdChamado() {
        const linhas = document.querySelectorAll("table tbody tr");
        let maiorId = 0;

        linhas.forEach((linha) => {
            const colunas = linha.querySelectorAll("td");
            if (colunas.length >= 2) {
                const idTexto = colunas[1].textContent.trim();
                const id = parseInt(idTexto, 10);
                if (!isNaN(id) && id > maiorId) {
                    maiorId = id;
                }
            }
        });

        return maiorId;
    }

    let maiorIdAnterior = obterMaiorIdChamado();
    console.log("Maior ID inicial:", maiorIdAnterior);

    const tabela = document.querySelector("table tbody");

    if (!tabela) {
        console.warn("Tabela não encontrada.");
        return;
    }

    const observer = new MutationObserver(() => {
        const novoMaiorId = obterMaiorIdChamado();
        if (novoMaiorId > maiorIdAnterior) {
            console.log(`🔔 Novo chamado detectado! ID: ${novoMaiorId}`);
            mostrarToast(`🔔 Novo chamado recebido! ID: ${novoMaiorId}`);
            maiorIdAnterior = novoMaiorId;
        }
    });

    observer.observe(tabela, { childList: true, subtree: true });
    console.log("Observador de mutações registrado.");

    function mostrarToast(mensagem) {
        const toast = document.createElement("div");
        toast.textContent = mensagem;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1d4ed8;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 9999;
            font-weight: bold;
            font-family: sans-serif;
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }
});
