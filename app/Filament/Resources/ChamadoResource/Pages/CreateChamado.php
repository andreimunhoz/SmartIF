<?php

namespace App\Filament\Resources\ChamadoResource\Pages;

use App\Filament\Resources\ChamadoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChamado extends CreateRecord
{
    protected static string $resource = ChamadoResource::class;

    protected function getRedirectUrl(): string
    {
        // Redireciona para a listagem de chamados após criação
        return $this->getResource()::getUrl('index');
    }
}
