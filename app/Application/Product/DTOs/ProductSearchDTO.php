<?php namespace App\Application\Product\DTOs;

use App\Application\Product\Requests\SearchProductsRequest;

readonly class ProductSearchDTO
{
    public function __construct(
        public ?string $q = null,
        public ?float $priceFrom = null,
        public ?float $priceTo = null,
        public ?int $categoryId = null,
        public ?bool $inStock = null,
        public ?float $ratingFrom = null,  // required? The error says argument #6
        public ?string $sort = null,
        public ?int $page = 1,
        public ?int $pageSize = 15
    ) {}

    /**
     * Map the validated Request data into the DTO
     */
    public static function fromRequest(SearchProductsRequest $request): self
    {
        return new self(
            q: $request->validated('q'),
            priceFrom: $request->validated('price_from') ? (float) $request->validated('price_from') : null,
            priceTo: $request->validated('price_to') ? (float) $request->validated('price_to') : null,
            categoryId: $request->validated('category_id') ? (int) $request->validated('category_id') : null,
            inStock: $request->has('in_stock') ? $request->boolean('in_stock') : null,
            ratingFrom: $request->validated('rating_from') ? (float) $request->validated('rating_from') : null,
            sort: $request->validated('sort', 'newest'),
            page: (int) $request->validated('page', 1),
            pageSize: (int) $request->validated('page_size', 15)
        );
    }
}
