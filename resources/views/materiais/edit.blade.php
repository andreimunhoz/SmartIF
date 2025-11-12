<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">
            Editar Item: {{ $material->nome }}
        </h1>
    </x-slot>

    <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg p-6 sm:p-8">
        
        <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">
            Detalhes do Material
        </h2>

        <form method="POST" action="{{ route('materiais.update', ['material' => $material]) }}">
    @csrf
    @method('PUT') <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                    Nome do Item
                </label>
                <input type="text" name="nome" id="nome"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                       value="{{ old('nome', $material->nome) }}"
                       required>
                @error('nome')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="quantidade_em_estoque" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                        Quantidade em Estoque
                    </label>
                    <input type="number" name="quantidade_em_estoque" id="quantidade_em_estoque"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                           value="{{ old('quantidade_em_estoque', $material->quantidade_em_estoque) }}"
                           min="0" required>
                    @error('quantidade_em_estoque')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="estoque_minimo" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                        Estoque Mínimo (Alerta)
                    </label>
                    <input type="number" name="estoque_minimo" id="estoque_minimo"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                           value="{{ old('estoque_minimo', $material->estoque_minimo) }}"
                           min="0" required>
                    @error('estoque_minimo')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="valor_de_custo" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                        Valor de Custo (Unitário)
                    </label>
                    <input type="number" name="valor_de_custo" id="valor_de_custo"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                           value="{{ old('valor_de_custo', $material->valor_de_custo) }}"
                           min="0" step="0.01" required>
                    @error('valor_de_custo')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="codigo_patrimonio" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                        Código de Património (Opcional)
                    </label>
                    <input type="text" name="codigo_patrimonio" id="codigo_patrimonio"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                           value="{{ old('codigo_patrimonio', $material->codigo_patrimonio) }}">
                    @error('codigo_patrimonio')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="data_de_validade" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                    Data de Validade (Opcional)
                </label>
                <input type="date" name="data_de_validade" id="data_de_validade"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                       value="{{ old('data_de_validade', $material->data_de_validade?->format('Y-m-d')) }}">
                @error('data_de_validade')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                    Descrição (Opcional)
                </label>
                <textarea name="descricao" id="descricao" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                          >{{ old('descricao', $material->descricao) }}</textarea>
                @error('descricao')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-gray-200 dark:border-admin-border pt-6">
                <a href="{{ route('materiais.index') }}" 
                   class="rounded-md px-4 py-2 text-sm font-semibold text-gray-700 dark:text-admin-text-secondary hover:bg-gray-100 dark:hover:bg-admin-border">
                    Cancelar
                </a>
                <button type="submit"
                        class="rounded-md bg-admin-accent-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-admin-accent-700 focus:outline-none focus:ring-2 focus:ring-admin-accent-500 focus:ring-offset-2">
                    Atualizar Item
                </button>
            </div>
        </form>
        
        <div class="mt-6 border-t border-red-200 dark:border-red-500/20 pt-6">
             <h3 class="text-lg font-semibold text-red-700 dark:text-red-400">Zona de Perigo</h3>
             <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">
                 Desativar este item irá removê-lo da lista de baixa de materiais, mas manterá o histórico.
             </p>
             <form method="POST" action="{{ route('materiais.destroy', ['material' => $material]) }}">
    @csrf
    @method('DELETE')
                 <button type="submit"
                         onclick="return confirm('Tem certeza que deseja desativar este item? Esta ação não pode ser desfeita.')"
                         class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                     Desativar Item
                 </button>
             </form>
        </div>
    </div>
</x-app-layout>