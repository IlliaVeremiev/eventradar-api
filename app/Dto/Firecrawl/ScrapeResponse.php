<?php

namespace App\Dto\Firecrawl;

use Spatie\LaravelData\Data;

class ScrapeResponse extends Data
{
    public function __construct(
        public bool $success,
        public ScrapeResponseData $data,
    ) {
    }
}
