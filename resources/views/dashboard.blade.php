<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">
            Dashboard
        </h1>
    </x-slot>

    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-admin-text-primary">Bem-vindo de volta, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-600 dark:text-admin-text-secondary">Aqui está um resumo da sua operação hoje.</p>
    </div>

    <div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            
            <div class="rounded-lg bg-white dark:bg-admin-card p-6 shadow-lg transition-all hover:shadow-xl hover:-translate-y-1 flex flex-col justify-center items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-admin-text-primary mb-2">Status Geral</h3>
                <div id="chart-chamados" class="h-32 w-32"></div>
            </div>
            
            <div class="rounded-lg bg-white dark:bg-admin-card p-6 shadow-lg transition-all hover:shadow-xl hover:-translate-y-1 flex flex-col items-center justify-between text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-admin-text-secondary">Em Aberto</p>
                <p class="text-5xl font-bold text-yellow-500 dark:text-yellow-400">{{ $chamadosAbertos }}</p>
                <div class="rounded-full bg-yellow-100 dark:bg-yellow-500/10 p-3 mt-2">
                    <ion-icon name="hourglass-outline" class="text-2xl text-yellow-600 dark:text-yellow-400"></ion-icon>
                </div>
            </div>
            
            <div class="rounded-lg bg-white dark:bg-admin-card p-6 shadow-lg transition-all hover:shadow-xl hover:-translate-y-1 flex flex-col items-center justify-between text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-admin-text-secondary">Em Andamento</p>
                <p class="text-5xl font-bold text-blue-500 dark:text-blue-400">{{ $chamadosEmAndamento }}</p>
                <div class="rounded-full bg-blue-100 dark:bg-blue-500/10 p-3 mt-2">
                    <ion-icon name="sync-outline" class="text-2xl text-blue-600 dark:text-blue-400"></ion-icon>
                </div>
            </div>
            
            <div class="rounded-lg bg-white dark:bg-admin-card p-6 shadow-lg transition-all hover:shadow-xl hover:-translate-y-1 flex flex-col items-center justify-between text-center">
                <p class="text-sm font-medium text-gray-500 dark:text-admin-text-secondary">Fechados</p>
                <p class="text-5xl font-bold text-admin-accent-600 dark:text-admin-accent-500">{{ $chamadosConcluidos }}</p>
                <div class="rounded-full bg-admin-accent-100 dark:bg-admin-accent-500/10 p-3 mt-2">
                    <ion-icon name="shield-checkmark-outline" class="text-2xl text-admin-accent-700 dark:text-admin-accent-400"></ion-icon>
                </div>
            </div>
        </div>

        <div class="mt-8 space-y-6">
            <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg transition-shadow hover:shadow-xl">
                <div class="flex items-center justify-between border-b p-6 border-gray-200 dark:border-admin-border">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">Itens com Estoque Baixo</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">Itens que atingiram ou estão abaixo do nível mínimo.</p>
                    </div>
                    <a href="{{ route('materiais.index') }}" class="text-sm font-semibold text-admin-accent-600 hover:text-admin-accent-700 dark:text-admin-accent-400 dark:hover:text-admin-accent-300">Ver Estoque →</a>
                </div>
                <div class="flow-root">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-admin-border">
                                <thead class="bg-gray-50 dark:bg-gray-800/50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Item</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Nível do Estoque</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-6"><span class="sr-only">Ações</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-admin-border">
                                    @forelse ($materiaisEstoqueBaixo as $material)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-gray-900 dark:text-admin-text-primary">{{ $material->nome }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-full bg-gray-200 dark:bg-admin-border rounded-full h-2.5">
                                                        @php
                                                            $percentual = ($material->estoque_minimo > 0) ? ($material->quantidade_em_estoque / $material->estoque_minimo) * 100 : 0;
                                                            $percentual = min($percentual, 100); // Não passa de 100%
                                                        @endphp
                                                        <div class="bg-red-500 h-2.5 rounded-full" style="width: {{ $percentual }}%"></div>
                                                    </div>
                                                    <span class="font-semibold text-red-600 dark:text-red-400">{{ $material->quantidade_em_estoque }} / {{ $material->estoque_minimo }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                                <a href="{{ route('materiais.edit', $material) }}" class="text-admin-accent-600 hover:text-admin-accent-700 dark:text-admin-accent-400 dark:hover:text-admin-accent-300">Ver Item</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-gray-500 dark:text-admin-text-secondary text-center">
                                                Nenhum item com estoque baixo. Ótimo trabalho!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg transition-shadow hover:shadow-xl">
                    <div class="flex items-center justify-between border-b p-6 border-gray-200 dark:border-admin-border">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">Atividade na Semana</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">Chamados abertos vs. fechados.</p>
                        </div>
                        <a href="#" class="text-sm font-semibold text-admin-accent-600 hover:text-admin-accent-700 dark:text-admin-accent-400 dark:hover:text-admin-accent-300">Ver Relatório →</a>
                    </div>
                    <div class="p-4">
                        <div id="weekly-activity-chart"></div>
                    </div>
                </div>

                <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg transition-shadow hover:shadow-xl">
                    <div class="flex items-center justify-between border-b p-6 border-gray-200 dark:border-admin-border">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">Atividade Recente</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">Últimos chamados abertos.</p>
                        </div>
                        <a href="{{ route('chamados.index') }}" class="text-sm font-semibold text-admin-accent-600 hover:text-admin-accent-700 dark:text-admin-accent-400 dark:hover:text-admin-accent-300">Ver Todos →</a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse ($atividadesRecentes as $chamado)
                            <div class="flex items-start gap-4">
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="https://placehold.co/100x100/f87171/450a0a?text={{ Str::upper(substr($chamado->requisitante->name, 0, 2)) }}" 
                                     alt="Avatar de {{ $chamado->requisitante->name }}" />
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-admin-text-primary">{{ $chamado->titulo }}</p>
                                    <p class="text-sm text-gray-500 dark:text-admin-text-secondary">
                                        {{ $chamado->requisitante->name }} 
                                        <span class="text-gray-400 dark:text-gray-500">· {{ $chamado->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                                @php
                                    $prioridadeClass = match($chamado->prioridade) {
                                        'baixa' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 ring-blue-200 dark:ring-blue-400/20',
                                        'media' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400 ring-yellow-200 dark:ring-yellow-400/20',
                                        'alta' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400 ring-red-200 dark:ring-red-400/20',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $prioridadeClass }}">
                                    {{ Str::ucfirst($chamado->prioridade) }}
                                </span>
                            </div>
                        @empty
                             <div class="text-sm text-gray-500 dark:text-admin-text-secondary text-center py-4">
                                Nenhuma atividade recente.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Passa os dados do PHP (Controller) para o JavaScript
        const donutData = @json($donutChartData);
        const totalChamados = @json($totalChamados);

        // Atualiza as opções do Gráfico Donut (que está em app.js)
        document.addEventListener("DOMContentLoaded", () => {
            if (window.donutChart) {
                window.donutChart.updateOptions({
                    series: donutData.series,
                    labels: donutData.labels,
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    total: {
                                        formatter: () => totalChamados
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // O gráfico de área (semanal) ainda usa dados estáticos do app.js
            // Fazer este gráfico dinâmico é um passo mais avançado.
        });
    </script>
    @endpush

</x-app-layout>