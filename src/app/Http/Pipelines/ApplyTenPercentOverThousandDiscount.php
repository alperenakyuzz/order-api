<?php

namespace App\Http\Pipelines;

use Closure;

class ApplyTenPercentOverThousandDiscount implements Pipe
{
	public function handle($order, Closure $next)
	{
		if ($order->total >= 1000) {
			$discount = new \stdClass();

			$calculate = round(($order->discounted_total * 10) / 100, 2);

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