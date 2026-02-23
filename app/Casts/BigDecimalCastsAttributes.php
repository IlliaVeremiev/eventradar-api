<?php

namespace App\Casts;

use Brick\Math\BigDecimal;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class BigDecimalCastsAttributes implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?BigDecimal
    {
        if ($value === null) {
            return null;
        }

        return BigDecimal::of($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }
}
