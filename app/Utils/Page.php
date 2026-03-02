<?php

namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class Page extends ResourceCollection
{
    /**
     * @template T of JsonResource
     *
     * @param  class-string<T>  $resourceClass
     */
    public function __construct(
        private readonly LengthAwarePaginator $paginator,
        string $resourceClass
    ) {
        parent::__construct($resourceClass::collection($paginator));
    }

    public function toArray(Request $request): array
    {
        return [
            'content' => $this->collection,
            'page' => $this->paginator->currentPage(),
            'size' => $this->paginator->perPage(),
            'totalPages' => $this->paginator->lastPage(),
            'totalElements' => $this->paginator->total(),
            'hasNext' => $this->paginator->hasMorePages(),
            'hasPrevious' => $this->paginator->currentPage() > 1,
        ];
    }
}
