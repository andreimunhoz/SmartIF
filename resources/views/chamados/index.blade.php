<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">
            Meus Chamados
        </h1>
    </x-slot>

    <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg transition-shadow hover:shadow-xl">
        
        <div class="flex items-center justify-between border-b p-6 border-gray-200 dark:border-admin-border">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">Listagem de Chamados</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">Todos os chamados abertos e em andamento.</p>
            </div>

            <a href="{{ route('chamados.create') }}" 
               class="group flex items-center gap-2 rounded-full bg-admin-accent-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-admin-accent-700 focus:outline-none focus:ring-2 focus:ring-admin-accent-500">
                <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
                <span>Novo Chamado</span>
            </a>
        </div>

        <div class="flow-root">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-admin-border">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">ID</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Título</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Requisitante</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Prioridade</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-6"><span class="sr-only">Ações</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-admin-border">
                            
                            @forelse ($chamados as $chamado)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-gray-900 dark:text-admin-text-primary">{{ $chamado->id }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-gray-800 dark:text-admin-text-primary">{{ $chamado->titulo }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-admin-text-secondary">{{ $chamado->requisitante->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @php
                                            $statusClass = match($chamado->status) {
                                                'aberto' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400 ring-yellow-200 dark:ring-yellow-400/20',
                                                'em_andamento' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 ring-blue-200 dark:ring-blue-400/20',
                                                'concluido' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 ring-green-200 dark:ring-green-400/20',
                                                'cancelado' => 'bg-gray-100 text-gray-700 dark:bg-gray-500/10 dark:text-gray-400 ring-gray-200 dark:ring-gray-400/20',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                            {{ Str::ucfirst(str_replace('_', ' ', $chamado->status)) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @php
                                            $prioridadeClass = match($chamado->prioridade) {
                                                'baixa' => 'text-blue-600 dark:text-blue-400',
                                                'media' => 'text-yellow-600 dark:text-yellow-400',
                                                'alta' => 'text-red-600 dark:text-red-400',
                                            };
                                        @endphp
                                        <span class="font-semibold {{ $prioridadeClass }}">{{ Str::ucfirst($chamado->prioridade) }}</span>
                                    </td>
                                    <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                        <a href="{{ route('chamados.show', $chamado) }}" class="text-admin-accent-600 hover:text-admin-accent-700 dark:text-admin-accent-400 dark:hover:text-admin-accent-300">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="whitespace-nowrap py-6 pl-6 pr-3 text-sm font-medium text-gray-500 dark:text-admin-text-secondary text-center">
                                        Nenhum chamado encontrado. <a href="{{ route('chamados.create') }}" class="text-admin-accent-600 hover:underline">Abra o primeiro!</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($chamados->hasPages())
            <div class="border-t p-6 border-gray-200 dark:border-admin-border">
                {{ $chamados->links() }}
            </div>
        @endif
    </div>
</x-app-layout>