<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chamado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'descricao',
        'patrimonio',
        'sala',
        'ramal',
        'departamento_id',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
