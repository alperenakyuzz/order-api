<?php

namespace App\Http\Pipelines;

use Closure;

class ApplyTwentyPercentOnSameCategoryDiscount implements Pipe
{
	public function handle($order, Closure $next)
	{
		$items = $order->items()->whereHas('product', function ($query) {
			$query->where('category_id', 1);
		})->get()->sortBy('unit_price');

		if ($items->count() >= 2) {
			$item = $items->first();

			$calculate = round(($item->total * 20) / 100, 2);

			$discount = new \stdClass();
			$discount->discount_reason = (new \ReflectionClass($this))->getShortName();
			$discount->discount_amount = $calculate;
			$discount->subtotal = round($order->discounted_total - $calculate, 2);
			$order->discounts->push($discount);
			$order->total_discount = round($order->total_discount + $calculate, 2);
			$order->discounted_total = round($order->discounted_total - $calculate, 2);
		}

		return $next($order);
	}
}