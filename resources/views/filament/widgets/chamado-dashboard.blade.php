<x-filament::widget>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $cards = [
                [
                    'title' => 'Chamados Abertos',
                    'count' => \App\Models\Chamado::where('status', 'aberto')->count(),
                    'color' => 'primary',
                    'icon' => 'heroicon-o-inbox',
                    'status' => 'aberto',
                ],
                [
                    'title' => 'Chamados em andamento',
                    'count' => \App\Models\Chamado::where('status', 'em_andamento')->count(),
                    'color' => 'yellow',
                    'icon' => 'heroicon-o-cog',
                    'status' => 'em_andamento',
                ],
                [
                    'title' => 'Chamados Encerrados',
                    'count' => \App\Models\Chamado::where('status', 'encerrado')->count(),
                    'color' => 'green',
                    'icon' => 'heroicon-o-check-circle',
                    'status' => 'encerrado',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <x-filament::card class="min-h-[220px] flex flex-col justify-between bg-{{ $card['color'] }}-50 border-l-4 border-{{ $card['color'] }}-600 shadow hover:shadow-lg transition-shadow duration-300">
                <div class="flex flex-col items-center text-center space-y-2">
                    <x-dynamic-component :component="$card['icon']" class="w-10 h-10 text-{{ $card['color'] }}-600" />
                    <h2 class="text-lg font-bold text-{{ $card['color'] }}-700 leading-snug">
                        {{ $card['title'] }}
                    </h2>
                    <p class="text-4xl font-bold text-{{ $card['color'] }}-600">
                        {{ $card['count'] }}
                    </p>
                </div>
                <div class="mt-4 text-center">
                    <a 
                        href="{{ route('filament.admin.resources.chamados.index', ['tableFilters[status][value]' => $card['status']]) }}"
                        class="text-sm text-{{ $card['color'] }}-700 hover:underline"
                    >
                        Ver detalhes
                    </a>
                </div>
            </x-filament::card>
        @endforeach
    </div>
</x-filament::widget>
