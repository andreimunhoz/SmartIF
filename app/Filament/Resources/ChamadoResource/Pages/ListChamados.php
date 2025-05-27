<?php

namespace App\Filament\Resources\ChamadoResource\Pages;

use App\Filament\Resources\ChamadoResource;
use App\Models\Chamado;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChamados extends ListRecords
{
    protected static string $resource = ChamadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTablePollingInterval(): ?string
    {
        return '5s'; 
    }

    public function getFooter(): ?\Illuminate\View\View
{
    return view('filament.alerta-chamado-novo-wrapper');
}

protected function getScripts(): array
{
    return [
        asset('js/alerta-chamado.js'),
    ];

}
}
