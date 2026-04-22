<?php namespace App\Domain\Category;

use App\Domain\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\Domain\Category\CategoryFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the products belonging to this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }


    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
