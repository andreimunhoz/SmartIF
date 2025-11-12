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
        // Tabela PIVOT para registrar a baixa de material em um chamado
        // Relação N:N (Muitos-para-Muitos) entre Chamados e Materiais
        Schema::create('chamado_material', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('chamado_id')
                  ->constrained('chamados')
                  ->onDelete('cascade'); // Se o chamado for deletado, esse registro some
            
            $table->foreignId('material_id')
                  ->constrained('materiais')
                  ->onDelete('restrict'); // Não deixa deletar um material se ele estiver em um chamado

            $table->unsignedInteger('quantidade_utilizada');
            
            // $table->foreignId('user_id')->constrained('users'); // Quem deu a baixa?
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamado_material');
    }
};