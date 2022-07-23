<?php

namespace App\Services;

use App\Http\Pipelines\ApplyBuyFiveGetOneDiscount;
use App\Http\Pipelines\ApplyTenPercentOverThousandDiscount;
use App\Http\Pipelines\ApplyTwentyPercentOnSameCategoryDiscount;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Pipeline;
use Illuminate\Validation\ValidationException;

class OrderService
{

	public function getAll(): Collection
	{
		return auth()->user()->orders()->with('items')->get();
	}

	public function get(
		int $id
	): Order {
		return Order::with('items')->findOrFail($id);
	}

	public function create(
		array $data
	): Order {
		$order = auth()->user()->orders()->create();
		$productService = app(ProductService::class);
		$items = [];

		foreach ($data['items'] as $item) {

			$product = $productService->get($item['product_id']);

			$items[] = [
				'product_id' => $product->id,
				'quantity'   => $item['quantity'],
				'unit_price' => $product->price,
				'total'      => $product->price * $item['quantity'],
			];
		}

		$order->items()
			->createMany($items);
		$order->total = $order->items()->sum('total');
		$order->save();

		return $order->refresh();

	}

	public function delete(int $id): bool
	{
		$order = $this->get($id);

		return $order->delete();
	}

	public function applyDiscounts(
		Order $order
	) {
		$pipes = [
			ApplyTenPercentOverThousandDiscount::class,
			ApplyBuyFiveGetOneDiscount::class,
			ApplyTwentyPercentOnSameCategoryDiscount::class,
		];

		$order->discounts = collect();

		return app(Pipeline::class)
			->send($order)
			->through($pipes)
			->then(function ($order) {
				return $order;
			});
	}

}