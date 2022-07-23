<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_id' => 'required', 'integer',
	        'items.*.product_id' => 'required|exists:products,id',
            'items.*' => ['required', function($attribute, $value, $fail) {
	            if(Product::findOrFail($value['product_id'])->stock < $value['quantity'])
		            $fail('Product ' . $value['product_id'] .' out of stock');
            }]
        ];
    }
}
