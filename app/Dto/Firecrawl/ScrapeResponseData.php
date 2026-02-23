<?php

namespace App\Dto\Firecrawl;

use Spatie\LaravelData\Data;

class ScrapeResponseData extends Data
{
    public function __construct(
        public string $markdown,
        public array $metadata,
    ) {
    }
}
