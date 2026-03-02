<?php

namespace App\Http\Requests;

use App\Dto\Search\EventsSearchDto;
use App\Utils\PageableRequest;

class EventsSearchRequest extends PageableRequest
{
    public function rules(): array
    {
        return [
            'query' => ['nullable', 'string'],
            'place' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'future' => ['nullable', 'in:true,false'],
        ];
    }


    public function toDto(): EventsSearchDto
    {
        return EventsSearchDto::from($this->all());
    }
}
