<?php declare(strict_types=1);

namespace Tests\Feature\Application\Product\Controllers;

use App\Application\Product\DTOs\ProductSearchDTO;
use App\Domain\Category\Category;
use App\Domain\Product\Product;
use App\Domain\Product\Services\ProductSearchService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class ProductSearchControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->actingAs(new \Illuminate\Auth\GenericUser(['id' => 1]));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function test_it_uses_default_pagination_values_when_not_provided(): void
    {
        $category = Category::factory()->make(['id' => 1, 'name' => 'Electronics']);

        $products = Product::factory()
            ->count(5)
            ->make()
            ->each(fn($product) => $product->setRelation('category', $category));

        $perPage = 15;
        $total = 15;
        $currentPage = 1;
        $path = '/api/products';

        $paginator = new LengthAwarePaginator(
            $products,
            $total,
            $perPage,
            $currentPage,
            ['path' => $path]
        );

        $serviceMock = Mockery::mock(ProductSearchService::class);
        $serviceMock->shouldReceive('search')
            ->once()
            ->with(Mockery::type(ProductSearchDTO::class))
            ->andReturn($paginator)
        ;

        $this->app->instance(ProductSearchService::class, $serviceMock);

        $response = $this->getJson('/api/products?q=watch');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'rating',
                    'in_stock',
                    'category' => ['id', 'name'],
                ],
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'from', 'last_page', 'per_page', 'to', 'total'],
        ]);

        $this->assertCount(5, $response->json('data'));
    }
}
