<?php

namespace App\Filament\Resources\ChamadoResource\Pages;

use App\Filament\Resources\ChamadoResource;
use App\Models\Chamado;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListChamados extends ListRecords
{
    protected static string $resource = ChamadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Novo Chamado'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos os Chamados')
                ->badge(Chamado::count()),

            'abertos' => Tab::make('Abertos')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'aberto'))
                ->badge(Chamado::where('status', 'aberto')->count())
                ->badgeColor('gray'),

            'em_andamento' => Tab::make('Em Andamento')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'em_andamento'))
                ->badge(Chamado::where('status', 'em_andamento')->count())
                ->badgeColor('warning'),

            'encerrados' => Tab::make('Encerrados')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'encerrado'))
                ->badge(Chamado::where('status', 'encerrado')->count())
                ->badgeColor('success'),
        ];
    }

    protected function getTablePollingInterval(): ?string
    {
        return '5s';
    }

}