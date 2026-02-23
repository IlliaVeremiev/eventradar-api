<?php

namespace App\Dto\Search;

use Spatie\LaravelData\Data;

class EventExtractionSession extends Data
{
    public function __construct(
        public string $date,
        public ?string $startTime,
        public ?string $endTime,
    ) {
    }
}
