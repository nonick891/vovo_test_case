<?php namespace Database\Factories\Domain\Product;

use App\Domain\Category\Category;
use App\Domain\Product\Product;
use Bezhanov\Faker\Provider\Commerce;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new Commerce($this->faker));

        return [
            'name' => $this->faker->productName,
            'price' => $this->faker->randomFloat(2, 10, 5000),
            'category_id' => Category::factory(),
            'in_stock' => $this->faker->boolean(80),
            'rating' => $this->faker->randomFloat(1, 0, 5),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the factory to also handle indexing after creation.
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $product->searchable();
        });
    }
}
