<?php

namespace App\Livewire\Synth;

use Brick\Math\BigDecimal;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class BigDecimalSynth extends Synth
{
    public static string $key = 'big_decimal_synth';

    public static function match($target): bool
    {
        return $target instanceof BigDecimal;
    }

    public function dehydrate(BigDecimal $target): array
    {
        return [(string) $target, []];
    }

    public static function hydrate($value): BigDecimal
    {
        return BigDecimal::of($value);
    }
}
