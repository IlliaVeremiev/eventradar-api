<?php

namespace App\Dto\Search;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class EventsSearchDto extends Data
{
    public function __construct(
        public readonly ?string $query,
        public readonly ?string $place,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly ?Carbon $date,
        public readonly ?bool $future,
    ) {
    }
}
