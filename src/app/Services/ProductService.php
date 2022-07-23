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

	public function create(array $data): array
	{
		$order = auth()->user()->orders()->create();
		foreach ($data['items'] as $item) {
			if(
				!$this->getProductStock($item['product_id'])
			) {
				$order->delete();
				throw ValidationException::withMessages([
					'product_id.'.$item['product_id'] => ['The selected product is out of stock.'],
				]);
			}
		}

		$order->items()->create([

		]);

	}

	private function getProductStock($productId): int
	{
		$product = Product::findOrFail($productId);
		return $product->stock ?? 0;
	}

}