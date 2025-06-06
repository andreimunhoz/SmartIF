// Função para exibir a notificação (toast)
function mostrarToast(id, nome) {
  // Previne a criação de toasts duplicados se o evento chegar rápido
  if (document.querySelector(`#toast-chamado-${id}`)) {
    return;
  }

  const toast = document.createElement("div");
  toast.id = `toast-chamado-${id}`; // Adiciona um ID para evitar duplicatas

  toast.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: start; max-width: 350px;">
            <div style="flex: 1; margin-right: 10px; color: #fff; font-family: sans-serif;">
                <div style="font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    🔔 Novo chamado recebido! ID: ${id}
                </div>
                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${nome}">
                    Solicitante: ${nome}
                </div>
            </div>
            <button class="toast-close-button" style="background: transparent; color: #fff; border: 1px solid #fff; padding: 4px 10px; border-radius: 4px; cursor: pointer; flex-shrink: 0; font-family: sans-serif; font-size: 14px; height: 38px; align-self: center;">
                Fechar
            </button>
        </div>
    `;
  // Estilos do toast
  toast.style.position = "fixed";
  toast.style.top = "20px";
  toast.style.right = "20px";
  toast.style.backgroundColor = "#2d3748";
  toast.style.padding = "12px 20px";
  toast.style.borderRadius = "8px";
  toast.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.2)";
  toast.style.zIndex = "9999";
  toast.style.fontSize = "14px";
  toast.style.lineHeight = "1.4";
  toast.style.marginTop = "10px";
  toast.style.transition = 'opacity 0.5s, transform 0.5s';
  toast.style.opacity = '0';
  toast.style.transform = 'translateY(-20px)';


  const closeButton = toast.querySelector(".toast-close-button");
  closeButton.addEventListener("click", () => {
    toast.remove();
  });

  document.body.appendChild(toast);

  // Animação de entrada
  setTimeout(() => {
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
  }, 50);

  // Animação de saída e remoção
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-20px)';
    setTimeout(() => toast.remove(), 500); // Remove após a transição
  }, 15000); // 15 segundos
}

// Ouve o evento do Livewire em toda a janela do navegador
// e chama a nossa função quando o evento 'novo-chamado-recebido' acontece.
window.addEventListener('novo-chamado-recebido', event => {
    // O event.detail contém os dados que enviamos do backend.
    // Usamos event.detail[0] por segurança, pois o Livewire às vezes encapsula em um array.
    const detail = event.detail[0] || event.detail;
    mostrarToast(detail.id, detail.nome);
});