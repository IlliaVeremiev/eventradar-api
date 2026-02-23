<?php

namespace App\Dto\Search;

use Spatie\LaravelData\Data;

class EventSearchResult extends Data
{
    public function __construct(
        public string $title,
        public string $url,
    ) {
    }
}
