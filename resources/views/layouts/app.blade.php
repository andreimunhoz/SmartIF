<!DOCTYPE html>
<html lang="pt-BR" class="font-sans">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Sistema IFSul</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  
  <style>
    /* A fonte 'Inter' será definida no tailwind.config.js, 
       mas manter isso aqui não tem problema. */
    body { font-family: 'Inter', sans-serif; }
  </style>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="h-full bg-slate-100 text-gray-700 dark:bg-admin-bg dark:text-admin-text-secondary">
  <div class="flex h-full">
    
    <div class="flex w-72 flex-col">
      <div class="flex h-16 shrink-0 items-center border-b border-r border-admin-accent-700 bg-admin-accent-600 px-8 shadow-md">
        <a href="#" class="flex items-center gap-2">
          <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4" />
            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
          </svg>
          <span class="font-bold text-xl text-white">Sistema IFSul</span>
        </a>
      </div>
      <div class="flex-1 p-4 pr-0">
        <aside class="flex h-full w-full flex-col overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-admin-card">
          <nav class="flex-1 space-y-2 p-4">
            <a href="{{ route('dashboard') }}" 
   class="group relative flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors 
          {{ request()->routeIs('dashboard') 
             ? 'text-admin-accent-600 font-semibold dark:text-admin-accent-300' 
             : 'text-gray-600 hover:bg-slate-100 hover:text-gray-900 dark:text-admin-text-secondary dark:hover:bg-gray-700 dark:hover:text-admin-text-primary' }}">
    
    @if(request()->routeIs('dashboard'))
        <span class="absolute left-0 top-1/2 h-6 w-1 -translate-y-1/2 rounded-r-full bg-admin-accent-500 dark:bg-admin-accent-400"></span>
    @endif
    
    <ion-icon name="grid" class="text-xl transition-transform group-hover:scale-110"></ion-icon>
    <span>Dashboard</span>
</a>
            <a href="{{ route('chamados.index') }}" 
   class="group relative flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors 
          {{ request()->routeIs('chamados.*') 
             ? 'text-admin-accent-600 font-semibold dark:text-admin-accent-300' 
             : 'text-gray-600 hover:bg-slate-100 hover:text-gray-900 dark:text-admin-text-secondary dark:hover:bg-gray-700 dark:hover:text-admin-text-primary' }}">

    @if(request()->routeIs('chamados.*'))
        <span class="absolute left-0 top-1/2 h-6 w-1 -translate-y-1/2 rounded-r-full bg-admin-accent-500 dark:bg-admin-accent-400"></span>
    @endif

    <ion-icon name="chatbox-ellipses-outline" class="text-xl transition-transform group-hover:scale-110"></ion-icon>
    <span>Chamados</span>
</a>
            <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-gray-600 hover:bg-slate-100 hover:text-gray-900 transition-colors dark:text-admin-text-secondary dark:hover:bg-gray-700 dark:hover:text-admin-text-primary">
              <ion-icon name="cube-outline" class="text-xl transition-transform group-hover:scale-110"></ion-icon>
              <span>Estoque</span>
            </a>
            <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-gray-600 hover:bg-slate-100 hover:text-gray-900 transition-colors dark:text-admin-text-secondary dark:hover:bg-gray-700 dark:hover:text-admin-text-primary">
              <ion-icon name="settings-outline" class="text-xl transition-transform group-hover:scale-110"></ion-icon>
              <span>Configurações</span>
            </a>
          </nav>
          <div class="border-t p-4 border-gray-200 dark:border-admin-border">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); this.closest('form').submit();"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-gray-600 hover:bg-red-50 hover:text-red-700 transition-colors dark:text-admin-text-secondary dark:hover:bg-red-500/10 dark:hover:text-red-400">
            <ion-icon name="log-out-outline" class="text-xl transition-transform group-hover:scale-110"></ion-icon>
            <span>Sair</span>
        </a>
    </form>
</div>
        </aside>
      </div>
    </div>

    <div class="flex flex-1 flex-col bg-transparent">
      
      <header class="relative z-10 flex h-16 shrink-0 items-center justify-between border-b border-admin-accent-700 bg-admin-accent-600 px-8 shadow-md">
        
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        
        <div class="flex items-center gap-4">
          
          <button id="theme-toggle" type="button" class="relative h-10 w-10 flex items-center justify-center rounded-full text-admin-accent-100 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">
            <ion-icon name="moon-outline" class="text-xl block dark:hidden"></ion-icon>
            <ion-icon name="sunny-outline" class="text-xl hidden dark:block"></ion-icon>
          </button>

          <div class="relative">
            <button id="notifications-button" type="button" class="relative h-10 w-10 flex items-center justify-center rounded-full text-admin-accent-100 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">
              <ion-icon name="notifications-outline" class="text-xl"></ion-icon>
              <span class="absolute top-1 right-1 h-2.5 w-2.5 rounded-full bg-red-500 border-2 border-admin-accent-600"></span>
            </button>
            <div id="notifications-menu" class="hidden absolute right-0 mt-2 w-80 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-admin-card dark:ring-admin-border z-50">
                <div class="p-4 border-b border-gray-200 dark:border-admin-border">
                  <h3 class="font-semibold text-gray-900 dark:text-admin-text-primary">Notificações</h3>
                </div>
                <div class="p-2 max-h-80 overflow-y-auto">
                    <a href="#" class="block p-2 rounded-md hover:bg-slate-100 dark:hover:bg-gray-700">
                        <p class="text-sm font-semibold text-gray-800 dark:text-admin-text-primary">Novo chamado de prioridade Alta</p>
                        <p class="text-xs text-gray-500 dark:text-admin-text-secondary">Criado por Ana Costa · há 2 min</p>
                    </a>
                </div>
            </div>
          </div>

          <button class="group flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-admin-accent-700 shadow-sm hover:bg-admin-accent-50 transition-colors focus:outline-none focus:ring-2 focus:ring-white">
            <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
            <span>Novo Chamado</span>
          </button>
          
          <div class="relative">
    <button id="user-menu-button" type="button" class="flex rounded-full focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-admin-accent-600">
        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-admin-accent-100/50" 
             src="https://placehold.co/100x100/cce8d9/004D29?text={{ Str::upper(substr(Auth::user()->name, 0, 2)) }}" 
             alt="{{ Auth::user()->name }}" />
    </button>
    
    <div id="user-menu" class="hidden absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-admin-card dark:ring-admin-border z-50" role="menu">
        <div class="border-b border-gray-200 dark:border-admin-border px-4 py-3">
            <p class="text-sm font-semibold text-gray-900 dark:text-admin-text-primary">
                {{ Auth::user()->name }}
            </p>
            <p class="text-xs text-gray-500 dark:text-admin-text-secondary truncate">
                {{ Auth::user()->email }}
            </p>
        </div>
        
        <div class="py-1">
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-slate-100 dark:text-admin-text-secondary dark:hover:bg-gray-700" role="menuitem">
                O meu Perfil
            </a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-slate-100 dark:text-admin-text-secondary dark:hover:bg-gray-700" role="menuitem">
                Configurações
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10" 
                   role="menuitem">
                    Sair
                </a>
            </form>
        </div>
    </div>
</div>
      </header>

      <main class="flex-1 overflow-y-auto p-8 pt-6">
        
        {{ $slot }}

      </main>
    </div>
  </div>

  </body>
</html>