<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ProductService
{

	public function get(int $productId): Product
	{
		return Product::findOrFail($productId);
	}

	public function reduceStock(
		Product $product, int $quantity
	): void
	{
		if(config('app.reduce_product_stock')) {
			$product->stock = $product->stock - $quantity;
			$product->save();
			$product->refresh();
		}
	}

}