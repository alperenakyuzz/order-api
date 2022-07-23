<?php

namespace App\Http\Pipelines;

use Closure;

class ApplyBuyFiveGetOneDiscount implements Pipe
{
	public function handle($order, Closure $next)
	{
		foreach ($order->items as $item) {
			if (
				$item->product->category_id == 2 &&
				$item->quantity == 6) {
				$discount = new \stdClass();
				$discount->discount_reason = (new \ReflectionClass($this))->getShortName();
				$discount->discount_amount = round($item->unit_price, 2);
				$discount->subtotal = round($order->discounted_total - $item->unit_price, 2);
				$order->discounts->push($discount);
				$order->total_discount = round($order->total_discount + $item->unit_price, 2);
				$order->discounted_total = round($order->discounted_total - $item->unit_price, 2);
			}
		}

		return $next($order);
	}
}