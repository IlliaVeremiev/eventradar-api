<?php

namespace App\Http\Requests;

use App\Utils\PageableRequest;

class EventsSearchRequest extends PageableRequest
{
    public function rules(): array
    {
        return [];
    }
}
