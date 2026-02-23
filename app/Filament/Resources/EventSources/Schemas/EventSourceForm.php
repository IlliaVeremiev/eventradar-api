<?php

namespace App\Filament\Resources\EventSources\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EventSourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->relationship('event', 'title')
                    ->required(),
                Textarea::make('url')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('domain')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
