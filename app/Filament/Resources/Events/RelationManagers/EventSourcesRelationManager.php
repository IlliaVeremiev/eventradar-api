<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Filament\Resources\EventSources\Schemas\EventSourceForm;
use App\Filament\Resources\EventSources\Tables\EventSourcesTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EventSourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'sources';

    protected static ?string $recordTitleAttribute = 'domain';

    public function form(Schema $schema): Schema
    {
        return EventSourceForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return EventSourcesTable::configure($table);
    }
}
