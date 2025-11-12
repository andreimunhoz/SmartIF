<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chamado extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'chamados';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'descricao_problema',
        'requisitante_id',
        'atendente_id',
        'status',
        'prioridade',
        'solucao_aplicada',
        'concluido_em',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'concluido_em' => 'datetime',
        // Enums (status, prioridade) são tratados como string por padrão no Laravel 11,
        // o que é geralmente suficiente.
    ];

    /**
     * Define o relacionamento 1:N (Inverso) com User.
     * Um chamado pertence a um usuário (requisitante).
     */
    public function requisitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requisitante_id');
    }

    /**
     * Define o relacionamento 1:N (Inverso) com User.
     * Um chamado pode pertencer a um usuário (atendente).
     */
    public function atendente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atendente_id');
    }

    /**
     * Define o relacionamento N:N (Muitos-para-Muitos) com Materiais.
     * Um chamado pode ter vários materiais (itens baixados).
     */
    public function materiais(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'chamado_material')
                    ->withPivot('quantidade_utilizada') // Traz a quantidade da tabela pivot
                    ->withTimestamps(); // Traz created_at/updated_at da tabela pivot
    }
}