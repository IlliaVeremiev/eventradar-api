<?php

namespace App\Dto\Searxng;

use Spatie\LaravelData\Data;

class SearchResponseResult extends Data
{
    public function __construct(
        public string $url,
        public string $title,
        public string $content,
        public string $engine,
    ) {
    }
}
