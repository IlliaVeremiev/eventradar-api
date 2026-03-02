<?php

namespace App\Utils;

interface Pageable
{
    public function getPage(): int;

    public function getSize(): int;
}
