<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Events\RelationManagers\EventSessionsRelationManager;
use App\Filament\Resources\Events\RelationManagers\EventSourcesRelationManager;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            EventSessionsRelationManager::class,
            EventSourcesRelationManager::class,
        ];
    }
}
