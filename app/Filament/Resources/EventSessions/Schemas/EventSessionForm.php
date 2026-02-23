<?php

namespace App\Filament\Resources\EventSessions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class EventSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->relationship('event', 'title')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('start_time'),
                TimePicker::make('end_time'),
            ]);
    }
}
