<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">
            Adicionar Novo Item ao Estoque
        </h1>
    </x-slot>

    <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg p-6 sm:p-8">
        
        <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">
            Detalhes do Material
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">
            Preencha os dados do novo item para o controle de estoque.
        </p>

        <form method="POST" action="{{ route('materiais.store') }}" class="mt-6 space-y-6">
            @csrf <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                    Nome do Item
                </label>
                <input type="text" name="nome" id="nome"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                       value="{{ old('nome') }}"
                       required>
                @error('nome')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="quantidade_em_estoque" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                        Quantidade Inicial
                    </label>
                    <input type="number" name="quantidade_em_estoque" id="quantidade_em_estoque"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                           value="{{ old('quantidade_em_estoque', 0) }}"
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
                           value="{{ old('estoque_minimo', 0) }}"
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
                           value="{{ old('valor_de_custo', 0.00) }}"
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
                           value="{{ old('codigo_patrimonio') }}">
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
                       value="{{ old('data_de_validade') }}">
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
                          >{{ old('descricao') }}</textarea>
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
                    Salvar Item
                </button>
            </div>
        </form>
    </div>
</x-app-layout>