<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Certifique-se de que esta linha está aqui

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal com estatísticas reais.
     */
    public function index()
    {
        // --- 1. Estatísticas dos Cards ---
        $chamadosAbertos = Chamado::where('status', 'aberto')->count();
        $chamadosEmAndamento = Chamado::where('status', 'em_andamento')->count();
        $chamadosConcluidos = Chamado::where('status', 'concluido')->count();
        $totalChamados = $chamadosAbertos + $chamadosEmAndamento + $chamadosConcluidos;

        // --- 2. Tabela de Estoque Baixo ---
        // Pega materiais onde a quantidade é menor ou igual ao mínimo
        $materiaisEstoqueBaixo = Material::whereColumn('quantidade_em_estoque', '<=', 'estoque_minimo')
                                        ->orderBy('quantidade_em_estoque', 'asc')
                                        ->take(5) // Limita a 5 para a tabela do dashboard
                                        ->get();

        // --- 3. Lista de Atividade Recente ---
        $atividadesRecentes = Chamado::with('requisitante') // Carrega o nome do requisitante
                                    ->orderBy('created_at', 'desc')
                                    ->take(5) // Pega os 5 mais recentes
                                    ->get();

        // --- 4. Dados para o Gráfico Donut ---
        // Prepara os dados para o ApexCharts
        $donutChartData = [
            'series' => [$chamadosAbertos, $chamadosEmAndamento, $chamadosConcluidos],
            'labels' => ['Em Aberto', 'Em Andamento', 'Concluídos'],
        ];

        // --- 5. Envia tudo para a View ---
        return view('dashboard', [
            'chamadosAbertos' => $chamadosAbertos,
            'chamadosEmAndamento' => $chamadosEmAndamento,
            'chamadosConcluidos' => $chamadosConcluidos,
            'totalChamados' => $totalChamados,
            'materiaisEstoqueBaixo' => $materiaisEstoqueBaixo,
            'atividadesRecentes' => $atividadesRecentes,
            'donutChartData' => $donutChartData,
        ]);
    }
}