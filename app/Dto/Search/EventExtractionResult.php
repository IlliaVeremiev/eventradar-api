<?php

namespace App\Dto\Search;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class EventExtractionResult extends Data
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $image,
        public ?string $timezone,
        public ?string $venueName,
        public ?string $address,
        public ?string $city,
        public ?string $state,
        public ?string $countryCode,
        public ?string $postalCode,
        #[DataCollectionOf(EventExtractionSession::class)]
        public DataCollection $sessions,
    ) {
    }
}
