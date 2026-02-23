<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->rows(5),
                TextInput::make('image')
                    ->columnSpanFull(),
                TextInput::make('timezone')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('venue_name')
                    ->columnSpanFull(),
                TextInput::make('address')
                    ->columnSpanFull(),
                TextInput::make('city')
                    ->columnSpanFull(),
                TextInput::make('state')
                    ->columnSpanFull(),
                TextInput::make('country_code')
                    ->columnSpanFull(),
                TextInput::make('postal_code')
                    ->columnSpanFull(),
            ]);
    }
}
