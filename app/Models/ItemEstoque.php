<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ItemEstoque extends Model
{
    use HasFactory;

    protected $table = 'itens_estoque';

    protected $fillable = [
        'nome',
        'quantidade',
        'unidade',
        'data_validade',
        'descricao',
        'imagem',
    ];

    protected static function booted()
    {
        static::updating(function ($item) {
            // Verifica se a imagem está sendo alterada e se havia uma imagem anterior
            if ($item->isDirty('imagem') && $item->getOriginal('imagem')) {
                // Remove a imagem antiga
                Storage::disk('public')->delete($item->getOriginal('imagem'));
            }
        });

        static::deleted(function ($item) {
            // Remove a imagem quando o item é deletado
            if ($item->imagem) {
                Storage::disk('public')->delete($item->imagem);
            }
        });
    }

    public function movimentos()
    {
        return $this->hasMany(MovimentoEstoque::class);
    }
}