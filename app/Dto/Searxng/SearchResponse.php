<?php

namespace App\Dto\Searxng;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SearchResponse extends Data
{
    public function __construct(
        public string $query,
        #[MapInputName('number_of_results')]
        public int $numberOfResults,
        #[DataCollectionOf(SearchResponseResult::class)]
        public DataCollection $results,
        /** @var array<string> $suggestions */
        public array $suggestions
    ) {
    }
}
