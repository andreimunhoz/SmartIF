<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'materiais';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'codigo_patrimonio',
        'quantidade_em_estoque',
        'estoque_minimo',
        'data_de_validade',
        'valor_de_custo',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_de_validade' => 'date',
        'valor_de_custo' => 'decimal:2',
    ];

    /**
     * Define o relacionamento N:N (Muitos-para-Muitos) com Chamados.
     * Um material pode estar em vários chamados.
     */
    public function chamados(): BelongsToMany
    {
        return $this->belongsToMany(Chamado::class, 'chamado_material')
                    ->withPivot('quantidade_utilizada') // Traz a quantidade da tabela pivot
                    ->withTimestamps(); // Traz created_at/updated_at da tabela pivot
    }
}