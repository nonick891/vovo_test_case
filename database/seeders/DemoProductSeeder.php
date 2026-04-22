<?php

namespace Database\Seeders;

use App\Domain\Product\Product;
use App\Domain\Category\Category;
use Illuminate\Database\Seeder;

class DemoProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics', 'Books', 'Clothing', 'Home & Garden',
            'Sports', 'Toys', 'Automotive', 'Health & Beauty'
        ];

        foreach ($categories as $catName) {
            Category::create(['name' => $catName]);
        }

        Product::withoutEvents(function () use ($categories) {
            Product::factory()->count(5000)->create();
        });

        Product::makeAllSearchable();

        $this->command->info('Demo categories and products created and indexed in Elasticsearch!');
    }
}
