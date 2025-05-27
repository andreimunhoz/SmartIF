<?php

namespace App\Livewire;

use App\Models\Chamado;
use Livewire\Component;

class AlertaChamadoNovo extends Component
{
    public $chamadoCount;

    public function mount()
    {
        $this->chamadoCount = Chamado::count();
    }

    public function checarNovosChamados()
    {
        $total = Chamado::count();

        if ($total > $this->chamadoCount) {
            // ✅ Esta é a forma correta no Livewire 3.0.x:
            $this->dispatch('novo-chamado-alerta')->self();

            $this->chamadoCount = $total;
        }
    }

    public function render()
    {
        $this->checarNovosChamados();
        return view('livewire.alerta-chamado-novo');
    }
}
