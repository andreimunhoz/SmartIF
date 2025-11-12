<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">
            Controlo de Estoque
        </h1>
    </x-slot>

    <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg transition-shadow hover:shadow-xl">
        
        <div class="flex items-center justify-between border-b p-6 border-gray-200 dark:border-admin-border">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">Itens em Estoque</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">Lista de todos os materiais registados.</p>
            </div>

            <a href="{{ route('materiais.create') }}" 
               class="group flex items-center gap-2 rounded-full bg-admin-accent-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-admin-accent-700 focus:outline-none focus:ring-2 focus:ring-admin-accent-500">
                <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
                <span>Novo Item</span>
            </a>
        </div>

        <div class="flow-root">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-admin-border">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Item</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Património</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Em Estoque</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-admin-text-primary">Estoque Mínimo</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-6"><span class="sr-only">Ações</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-admin-border">
                            
                            @forelse ($materiais as $material)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-semibold text-gray-800 dark:text-admin-text-primary">{{ $material->nome }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-admin-text-secondary">{{ $material->codigo_patrimonio ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium">
                                        @if($material->quantidade_em_estoque <= $material->estoque_minimo)
                                            <span class="text-red-600 dark:text-red-400 font-bold flex items-center gap-1">
                                                <ion-icon name="warning" class="text-base"></ion-icon>
                                                {{ $material->quantidade_em_estoque }} (Baixo)
                                            </span>
                                        @else
                                            <span class="text-gray-900 dark:text-admin-text-primary">{{ $material->quantidade_em_estoque }}</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-admin-text-secondary">{{ $material->estoque_minimo }}</td>
                                    <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                        <a href="{{ route('materiais.edit', $material) }}" class="text-admin-accent-600 hover:text-admin-accent-700 dark:text-admin-accent-400 dark:hover:text-admin-accent-300">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="whitespace-nowrap py-6 pl-6 pr-3 text-sm font-medium text-gray-500 dark:text-admin-text-secondary text-center">
                                        Nenhum material registado. <a href="{{ route('materiais.create') }}" class="text-admin-accent-600 hover:underline">Registe o primeiro!</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($materiais->hasPages())
            <div class="border-t p-6 border-gray-200 dark:border-admin-border">
                {{ $materiais->links() }}
            </div>
        @endif
    </div>
</x-app-layout>