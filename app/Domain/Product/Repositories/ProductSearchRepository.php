<?php

namespace App\Domain\Product\Repositories;

use App\Application\Product\DTOs\ProductSearchDTO;
use App\Domain\Product\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductSearchRepository {
    public function search(ProductSearchDTO $dto) : LengthAwarePaginator
    {
        $search = Product::search($dto->q ?? '');

        $search->query(function ($query) use ($dto) {
            if ($dto->priceFrom !== null) {
                $query->where('price', '>=', $dto->priceFrom);
            }

            if ($dto->priceTo !== null) {
                $query->where('price', '<=', $dto->priceTo);
            }

            if ($dto->categoryId) {
                $query->where('category_id', $dto->categoryId);
            }

            $query->with('category');
        });

        if ($dto->inStock !== null) {
            $search->where('in_stock', $dto->inStock);
        }

        match ($dto->sort) {
            'price_asc' => $search->orderBy('price'),
            'price_desc' => $search->orderBy('price', 'desc'),
            'rating_desc' => $search->orderBy('rating', 'desc'),
            'rating_asc' => $search->orderBy('rating'),
            'newest' => $search->orderByDesc('created_at'),
            default => $search->orderBy('id', 'desc')
        };

        return $search->paginate(
            $dto->pageSize ?? 15,
            'page',
            $dto->page ?? 1
        );
    }
}
