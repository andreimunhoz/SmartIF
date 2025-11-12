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
        // Tabela para o cadastro de itens do almoxarifado (Estoque)
        Schema::create('materiais', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->text('descricao')->nullable();
            $table->string('codigo_patrimonio', 100)->nullable()->unique(); // Código de identificação
            $table->unsignedInteger('quantidade_em_estoque')->default(0);
            $table->unsignedInteger('estoque_minimo')->default(0);

            // Campos adicionados para controle de validade e custo
            $table->date('data_de_validade')->nullable()->comment('Data de validade do material, se aplicável');
            $table->decimal('valor_de_custo', 10, 2)->default(0.00)->comment('Valor de custo unitário para relatórios');
            
            // $table->foreignId('categoria_id')->nullable()->constrained('categorias_materiais'); // Opcional: para organizar
            $table->timestamps();
            $table->softDeletes(); // Para "desativar" um item sem apagar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiais');
    }
};