<?php

namespace App\Application\Product\Controllers;

use App\Application\Controller;
use App\Application\Product\DTOs\ProductSearchDTO;
use App\Application\Product\Requests\SearchProductsRequest;
use App\Application\Product\Resources\ProductResource;
use App\Domain\Product\Services\ProductSearchService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductSearchController extends Controller
{

    public function search(SearchProductsRequest $request, ProductSearchService $service): AnonymousResourceCollection
    {
        $dto = ProductSearchDTO::fromRequest($request);

        $paginator = $service->search($dto);

        return ProductResource::collection($paginator->items());
    }

}
