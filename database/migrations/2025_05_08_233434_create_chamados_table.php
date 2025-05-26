<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->text('descricao');
            $table->string('patrimonio')->default('0');
            $table->string('sala');
            $table->string('ramal');
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->string('status')->default('aberto'); 
            $table->text('solucao')->nullable();
            $table->boolean('encerrado')->default(false);
            $table->timestamp('encerrado_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chamados');
    }
};
