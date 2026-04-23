<?php

namespace app\Application\Product\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => 'nullable|string|max:255',

            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0|gte:price_from',

            'category_id' => 'nullable|integer|exists:categories,id',

            'in_stock' => 'nullable|boolean',

            'rating_from' => 'nullable|numeric|min:0|max:5',

            'sort' => [
                'nullable',
                Rule::in(['price_asc', 'price_desc', 'rating_desc', 'newest']),
            ],

            'page' => 'nullable|integer|min:1',
            'page_size' => 'nullable|integer|min:1|max:100'
        ];
    }
}
