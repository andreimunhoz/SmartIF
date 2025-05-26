<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimentos_estoque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_estoque_id')->constrained('itens_estoque')->onDelete('cascade');
            $table->foreignId('chamado_id')->constrained('chamados')->onDelete('cascade');
            $table->integer('quantidade');
            $table->enum('tipo', ['entrada', 'saida']); // Exemplo de tipo de movimento
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentos_estoque');
    }
};
