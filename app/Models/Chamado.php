<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Livewire\Livewire; // <-- 1. IMPORTAÇÃO ADICIONADA

class Chamado extends Model
{
    use HasFactory;

    /**
     * ATENÇÃO: Adicionei 'solucao', 'encerrado' e 'encerrado_em' aqui.
     * Sem eles, a lógica para encerrar um chamado no seu ChamadoResource não funcionaria,
     * pois o método fill() ignoraria esses campos.
     */
    protected $fillable = [
        'nome', 'email', 'descricao', 'patrimonio', 'sala', 'ramal', 
        'departamento_id', 'status', 'solucao', 'encerrado', 'encerrado_em'
    ];

    /**
     * Define os atributos que devem ser convertidos para tipos nativos.
     * Adicionado para garantir que 'encerrado' seja sempre booleano.
     */
    protected $casts = [
        'encerrado' => 'boolean',
        'encerrado_em' => 'datetime',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function itensEstoque()
    {
        // Seu relacionamento foi mantido como estava
        return $this->belongsToMany(ItemEstoque::class, 'movimentos_estoque', 'chamado_id', 'item_estoque_id')
                    ->withPivot('quantidade');
    }

    // <-- 2. MÉTODO ADICIONADO
    /**
     * Este método é executado quando o model é inicializado.
     * Usamos o evento 'created' para disparar uma notificação
     * para o frontend sempre que um novo chamado for criado.
     */
    protected static function booted(): void
    {
        static::created(function (Chamado $chamado) {
            // Dispara um evento para o navegador com os dados do novo chamado
            Livewire::dispatch('novo-chamado-recebido', [
                'id' => $chamado->id,
                'nome' => $chamado->nome,
            ]);
        });
    }
}