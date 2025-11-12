<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabela principal para os chamados/requisições
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao_problema');
            
            // Chaves Estrangeiras para Usuários
            $table->foreignId('requisitante_id')
                  ->constrained('users')
                  ->comment('ID do usuário que abriu o chamado');
            
            $table->foreignId('atendente_id')
                  ->nullable()
                  ->constrained('users')
                  ->comment('ID do usuário (COMAG) que está atendendo');

            // Status e Prioridade (vistos no design)
            $table->enum('status', ['aberto', 'em_andamento', 'concluido', 'cancelado'])
                  ->default('aberto');
            
            $table->enum('prioridade', ['baixa', 'media', 'alta'])
                  ->default('baixa');

            $table->text('solucao_aplicada')->nullable(); // Descrição de como foi resolvido
            $table->timestamp('concluido_em')->nullable(); // Data de fechamento
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamados');
    }
};