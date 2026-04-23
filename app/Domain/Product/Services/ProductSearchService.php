<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Repositories\ProductSearchRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductSearchService {

    public function __construct(public ProductSearchRepository $repository)
    {}

    public function search($dto) : LengthAwarePaginator
    {
        return $this->repository->search($dto);
    }
}
