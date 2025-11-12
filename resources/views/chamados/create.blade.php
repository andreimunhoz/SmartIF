<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">
            Abrir Novo Chamado
        </h1>
    </x-slot>

    <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg p-6 sm:p-8">
        
        <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">
            Detalhes do Chamado
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-admin-text-secondary">
            Descreva o problema ou solicitação o mais detalhadamente possível.
        </p>

        <form method="POST" action="{{ route('chamados.store') }}" class="mt-6 space-y-6">
            @csrf <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                    Título
                </label>
                <input type="text" name="titulo" id="titulo"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                       value="{{ old('titulo') }}"
                       required>
                @error('titulo')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="prioridade" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                    Prioridade
                </label>
                <select name="prioridade" id="prioridade"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                        required>
                    <option value="baixa" @selected(old('prioridade') == 'baixa')>Baixa</option>
                    <option value="media" @selected(old('prioridade') == 'media')>Média</option>
                    <option value="alta" @selected(old('prioridade') == 'alta')>Alta</option>
                </select>
                @error('prioridade')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="descricao_problema" class="block text-sm font-medium text-gray-700 dark:text-admin-text-secondary">
                    Descrição do Problema
                </label>
                <textarea name="descricao_problema" id="descricao_problema" rows="5"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-admin-accent-500 focus:ring-admin-accent-500 dark:bg-admin-bg dark:border-admin-border dark:text-admin-text-primary"
                          required>{{ old('descricao_problema') }}</textarea>
                @error('descricao_problema')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-4 border-t border-gray-200 dark:border-admin-border pt-6">
                <a href="{{ route('chamados.index') }}" 
                   class="rounded-md px-4 py-2 text-sm font-semibold text-gray-700 dark:text-admin-text-secondary hover:bg-gray-100 dark:hover:bg-admin-border">
                    Cancelar
                </a>
                <button type="submit"
                        class="rounded-md bg-admin-accent-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-admin-accent-700 focus:outline-none focus:ring-2 focus:ring-admin-accent-500 focus:ring-offset-2">
                    Salvar Chamado
                </button>
            </div>
        </form>
    </div>
</x-app-layout>