<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">
            Atender Chamado #{{ $chamado->id }}
        </h1>
    </x-slot>

    <form method="POST" action="{{ route('chamados.update', $chamado) }}">
        @csrf
        @method('PUT') <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">
                        Andamento do Atendimento
                    </h2>

                    <div class="mt-6 space-y-6">
                        @if(!$chamado->atendente)
                        <div class="flex items-start">
                            <div class="flex h-6 items-center">
                                <input id="atribuir_a_mim" name="atribuir_a_mim" type="checkbox"
                                       class="h-4 w-4 rounded border-gray-300 text-admin-accent-600 focus:ring-admin-accent-500 dark:bg-admin-border">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="atribuir_a_mim" class="font-medium text-gray-900 dark:text-admin-text-primary">
                                    Atribuir a mim
                                </label>
                                <p class="text-gray-500 dark:text-admin-text-secondary">Marque para se tornar o atendente deste chamado.</p>
                            </div>
                        </div>
                        @endif

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                                Alterar Status
                            </label>
                            <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                                    required>
                                <option value="aberto" @selected($chamado->status == 'aberto')>Aberto</option>
                                <option value="em_andamento" @selected($chamado->status == 'em_andamento')>Em Andamento</option>
                                <option value="concluido" @selected($chamado->status == 'concluido')>Concluído</option>
                                <option value="cancelado" @selected($chamado->status == 'cancelado')>Cancelado</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="solucao_aplicada" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                                Solução Aplicada
                            </label>
                            <textarea name="solucao_aplicada" id="solucao_aplicada" rows="5"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                                      placeholder="Descreva a solução aplicada (obrigatório se o status for 'Concluído')."
                                      >{{ $chamado->solucao_aplicada }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">
                            Baixa de Material
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">
                            Selecione a quantidade de itens utilizados neste chamado.
                        </p>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-admin-border p-6 space-y-4 max-h-96 overflow-y-auto">
                        @if($materiais->isEmpty())
                            <p class="text-sm text-gray-500 dark:text-admin-text-secondary text-center">Nenhum material com estoque disponível.</p>
                        @else
                            @foreach($materiais as $material)
                            <div class="flex items-center justify-between gap-4">
                                <label for="material_{{ $material->id }}" class="text-sm font-medium text-gray-900 dark:text-admin-text-primary">
                                    {{ $material->nome }}
                                    <span class="block text-xs text-gray-500 dark:text-admin-text-secondary">({{ $material->quantidade_em_estoque }} em estoque)</span>
                                </label>
                                <input type="number" 
                                       name="materiais_utilizados[{{ $material->id }}]" 
                                       id="material_{{ $material->id }}"
                                       min="0"
                                       max="{{ $material->quantidade_em_estoque }}"
                                       placeholder="0"
                                       class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary">
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 mt-6 flex items-center justify-end gap-4">
                <a href="{{ route('chamados.show', $chamado) }}" 
                   class="rounded-md px-4 py-2 text-sm font-semibold text-gray-700 dark:text-admin-text-secondary hover:bg-gray-100 dark:hover:bg-admin-border">
                    Cancelar
                </a>
                <button type="submit"
                        class="rounded-md bg-admin-accent-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-admin-accent-700 focus:outline-none focus:ring-2 focus:ring-admin-accent-500 focus:ring-offset-2">
                    Salvar Atendimento
                </button>
            </div>
        </div>
    </form>
</x-app-layout>