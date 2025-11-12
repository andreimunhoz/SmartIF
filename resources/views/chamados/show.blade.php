<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">
                Detalhes do Chamado #{{ $chamado->id }}
            </h1>
            <a href="{{ route('chamados.edit', $chamado) }}" 
               class="rounded-md bg-yellow-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-white">
                Atender Chamado
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg">
            <div class="p-6 sm:p-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">
                            {{ $chamado->titulo }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">
                            Aberto por <span class="font-semibold">{{ $chamado->requisitante->name }}</span> em {{ $chamado->created_at->format('d/m/Y \à\s H:i') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $statusClass = match($chamado->status) {
                                'aberto' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400 ring-yellow-200 dark:ring-yellow-400/20',
                                'em_andamento' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 ring-blue-200 dark:ring-blue-400/20',
                                'concluido' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 ring-green-200 dark:ring-green-400/20',
                                'cancelado' => 'bg-gray-100 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400 ring-gray-200 dark:ring-gray-400/20',
                            };
                            $prioridadeClass = match($chamado->prioridade) {
                                'baixa' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 ring-blue-200 dark:ring-blue-400/20',
                                'media' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400 ring-yellow-200 dark:ring-yellow-400/20',
                                'alta' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400 ring-red-200 dark:ring-red-400/20',
                            };
                        @endphp
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                            {{ Str::ucfirst(str_replace('_', ' ', $chamado->status)) }}
                        </span>
                         <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $prioridadeClass }}">
                            Prioridade {{ Str::ucfirst($chamado->prioridade) }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 dark:border-admin-border pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-admin-text-primary">Descrição do Problema</h3>
                    <p class="mt-2 text-sm text-gray-700 dark:text-admin-text-secondary whitespace-pre-wrap">
                        {{ $chamado->descricao_problema }}
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg">
             <div class="p-6 sm:p-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-admin-text-primary">Andamento do Atendimento</h3>

                <div class="mt-4 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-admin-text-secondary">Atendente</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-admin-text-primary">
                            @if($chamado->atendente)
                                {{ $chamado->atendente->name }}
                            @else
                                <span class="italic text-gray-400">Aguardando atribuição</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-admin-text-secondary">Solução Aplicada</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-admin-text-primary">
                             @if($chamado->solucao_aplicada)
                                {{ $chamado->solucao_aplicada }}
                            @else
                                <span class="italic text-gray-400">Nenhuma solução registrada.</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-admin-text-secondary">Materiais Utilizados</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-admin-text-primary">
                             @if($chamado->materiais->isEmpty())
                                <span class="italic text-gray-400">Nenhum material baixado.</span>
                             @else
                                <ul class="list-disc list-inside">
                                    @foreach($chamado->materiais as $material)
                                        <li>{{ $material->nome }} ({{ $material->pivot->quantidade_utilizada }} un.)</li>
                                    @endforeach
                                </ul>
                             @endif
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>