<?php namespace App\Domain\Product;

use App\Domain\Category\Category;
use Database\Factories\Domain\Product\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, Searchable;

    protected $fillable = ['name', 'price', 'category_id', 'in_stock', 'rating'];

    protected $casts = [
        'in_stock' => 'boolean',
        'rating' => 'float',
        'price' => 'decimal:2',
    ];

    /**
     * Relationship with Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define what gets indexed in Elasticsearch.
     * Includes related category name for cross‑model searching.
     */
    public function toSearchableArray(): array
    {
        return [
            'name'        => $this->name,
            'price'       => (float) $this->price,
            'category_id' => $this->category_id,
            'in_stock'    => $this->in_stock,
            'rating'      => $this->rating,
        ];
    }

    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}
