<?php

namespace App\Application\Product\Controllers;

use App\Application\Controller;
use App\Application\Product\DTOs\ProductSearchDTO;
use App\Application\Product\Requests\SearchProductsRequest;
use App\Application\Product\Resources\ProductResource;
use App\Domain\Product\Services\ProductSearchService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", description: "Searchable product catalog with filtering, sorting, and pagination", title: "Product Catalog API")]
#[OA\Server(url: "http://localhost", description: "Local development server")]
class ProductSearchController extends Controller
{
    #[OA\Get(
        path: "/api/products",
        description: "Perform a full-text search across products with optional filters for price range, category, stock availability, and rating. Results can be sorted and paginated.",
        summary: "Search and filter products",
        tags: ["Products"],
        parameters: [
            new OA\Parameter(name: "q", description: "Full-text search query", in: "query", required: false, schema: new OA\Schema(type: "string", maxLength: 255)),
            new OA\Parameter(name: "price_from", description: "Minimum price filter", in: "query", required: false, schema: new OA\Schema(type: "number", minimum: 0)),
            new OA\Parameter(name: "price_to", description: "Maximum price filter", in: "query", required: false, schema: new OA\Schema(type: "number", minimum: 0)),
            new OA\Parameter(name: "category_id", description: "Filter by category ID", in: "query", required: false, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "in_stock", description: "Filter by stock availability", in: "query", required: false, schema: new OA\Schema(type: "boolean")),
            new OA\Parameter(name: "rating_from", description: "Minimum rating filter (0–5)", in: "query", required: false, schema: new OA\Schema(type: "number", maximum: 5, minimum: 0)),
            new OA\Parameter(name: "sort", description: "Sort order", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["price_asc", "price_desc", "rating_asc", "rating_desc", "newest"])),
            new OA\Parameter(name: "page", description: "Page number (1-based)", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 1, minimum: 1)),
            new OA\Parameter(name: "page_size", description: "Number of items per page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 15, maximum: 100, minimum: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "A paginated list of products",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Product")
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The price_from field must be a number."),
                        new OA\Property(
                            property: "errors",
                            type: "object",
                            additionalProperties: new OA\AdditionalProperties(
                                type: "array",
                                items: new OA\Items(type: "string")
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    public function search(SearchProductsRequest $request, ProductSearchService $service): AnonymousResourceCollection
    {
        $dto = ProductSearchDTO::fromRequest($request);

        $paginator = $service->search($dto);

        return ProductResource::collection($paginator->items());
    }
}

#[OA\Schema(
    schema: "Product",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "Watch"),
        new OA\Property(property: "price", type: "number", format: "float", example: 2499.99),
        new OA\Property(property: "rating", type: "number", format: "float", example: 4.5),
        new OA\Property(property: "in_stock", type: "boolean", example: true),
        new OA\Property(property: "category_id", type: "integer", example: 3),
        new OA\Property(
            property: "category",
            properties: [
                new OA\Property(property: "id", type: "integer"),
                new OA\Property(property: "name", type: "string"),
            ],
            type: "object",
            nullable: true
        ),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-04-22T12:00:00"),
    ],
    type: "object"
)]
class ProductSwaggerSchema {}
