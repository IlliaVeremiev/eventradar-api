<?php

namespace App\Services;

interface FirecrawlService
{
    public function fetchMarkdown(string $url): string;
}
