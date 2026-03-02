<?php

namespace App\Utils;

use Illuminate\Foundation\Http\FormRequest;

class PageableRequest extends FormRequest implements Pageable
{
    public const int DEFAULT_SIZE = 10;

    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'size' => ['integer', 'min:1', 'max:100'],
        ];
    }

    public function getPage(): int
    {
        return $this->integer('page', 0);
    }

    public function getSize(): int
    {
        return $this->integer('size', self::DEFAULT_SIZE);
    }
}
