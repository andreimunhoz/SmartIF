<?php

namespace App\Events;

use App\Models\Chamado;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel; // para canais privados
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NovoChamadoCriado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Chamado $chamado;

    public function __construct(Chamado $chamado)
    {
        $this->chamado = $chamado;
    }

    // Canal privado para assinantes autenticados (admin)
    public function broadcastOn()
    {
        return new PrivateChannel('chamados');
    }

    // Nome do evento no front-end (opcional)
    public function broadcastAs()
    {
        return 'NovoChamadoCriado';
    }
}
