<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-white">
            Meus Chamados
        </h1>
    </x-slot>

    <div class="rounded-lg bg-white dark:bg-admin-card shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-admin-text-primary">
            Lista de Chamados
        </h2>
        
        <p class="mt-2 text-gray-600 dark:text-admin-text-secondary">
            Aqui será exibida a tabela com todos os chamados abertos.
        </p>

        <div class="mt-6">
            <p>Carregando tabela...</p>
        </div>
    </div>
    
</x-app-layout>