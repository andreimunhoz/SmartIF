<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificacaoNovoChamado extends Component
{
    public $mostrarNotificacao = false;
    public $mensagem = '';

    protected $listeners = [
        'echo:chamados,NovoChamadoCriado' => 'handleNovoChamado',
    ];

    public function handleNovoChamado($payload)
    {
        $this->mensagem = "Novo chamado aberto: " . $payload['nome'] ?? 'Chamado';
        $this->mostrarNotificacao = true;

        // Ocultar notificação depois de 5 segundos
        $this->dispatchBrowserEvent('ocultarNotificacao', ['tempo' => 5000]);
    }

    public function fecharNotificacao()
    {
        $this->mostrarNotificacao = false;
    }

    public function render()
    {
        return view('livewire.notificacao-novo-chamado');
    }
}
