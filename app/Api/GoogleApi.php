<?php

namespace App\Api;

interface GoogleApi
{
    public function verifyIdToken(string $credential): array;
}
