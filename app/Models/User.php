<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- ADICIONE ESTES MÉTODOS ---

    /**
     * Define o relacionamento 1:N (Um-para-Muitos) com Chamado.
     * Um usuário pode ter vários chamados (requisitados).
     */
    public function chamadosRequisitados(): HasMany
    {
        return $this->hasMany(Chamado::class, 'requisitante_id');
    }

    /**
     * Define o relacionamento 1:N (Um-para-Muitos) com Chamado.
     * Um usuário pode ter vários chamados (atendidos).
     */
    public function chamadosAtendidos(): HasMany
    {
        return $this->hasMany(Chamado::class, 'atendente_id');
    }
}