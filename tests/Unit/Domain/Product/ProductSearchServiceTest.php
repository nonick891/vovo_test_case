<?php declare(strict_types=1);

namespace Tests\Unit\Domain\Product;

use App\Application\Product\DTOs\ProductSearchDTO;
use App\Domain\Product\Services\ProductSearchService;
use App\Domain\Product\Repositories\ProductSearchRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class ProductSearchServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     */
    public function test_it_calls_repository_search_with_dto_and_returns_paginator(): void
    {
        // Arrange
        $dto = new ProductSearchDTO(
            q: 'laptop',
            priceFrom: 500,
            priceTo: 1500,
            categoryId: 5,
            inStock: true,
            ratingFrom: null,
            sort: null,
            page: 2,
            pageSize: 20
        );

        $expectedPaginator = Mockery::mock(LengthAwarePaginator::class);

        $repositoryMock = Mockery::mock(ProductSearchRepository::class);
        $repositoryMock->shouldReceive('search')
            ->once()
            ->with($dto)
            ->andReturn($expectedPaginator);

        $service = new ProductSearchService($repositoryMock);

        $result = $service->search($dto);

        $this->assertSame($expectedPaginator, $result);
    }

    /**
     * @test
     */
    public function test_it_passes_minimal_dto_without_optional_fields(): void
    {
        $dto = new ProductSearchDTO(q: 'phone'); // only required fields

        $paginatorMock = Mockery::mock(LengthAwarePaginator::class);

        $repositoryMock = Mockery::mock(ProductSearchRepository::class);
        $repositoryMock->shouldReceive('search')
            ->once()
            ->with($dto)
            ->andReturn($paginatorMock);

        $service = new ProductSearchService($repositoryMock);

        $result = $service->search($dto);

        $this->assertSame($paginatorMock, $result);
    }
}
