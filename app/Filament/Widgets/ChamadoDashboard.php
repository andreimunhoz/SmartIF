<?php

namespace App\Filament\Widgets;

use App\Models\Chamado;
use Filament\Widgets\Widget;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\PieChart;

class ChamadoDashboard extends Widget
{
    protected static string $view = 'filament.widgets.chamado-dashboard';

    public function getData(): array
    {
        // Contar os chamados por status
        $statuses = Chamado::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Definir os rótulos para os status
        $labels = ['Aberto', 'Em andamento', 'Encerrado'];

        return [
            'chartData' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => [
                            $statuses['aberto'] ?? 0,
                            $statuses['em_andamento'] ?? 0,
                            $statuses['encerrado'] ?? 0,
                        ],
                        'backgroundColor' => ['#ff6384', '#ffcd56', '#36a2eb'],
                    ],
                ],
            ],
        ];
    }
}
