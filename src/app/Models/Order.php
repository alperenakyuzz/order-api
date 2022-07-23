<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
	use HasFactory;

	protected $fillable = ['customer_id', 'total'];

	protected static function boot()
	{
		parent::boot();

		static::retrieved(function ($order) {
			$order->discounted_total = $order->total;
		});
	}

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function items(): HasMany
	{
		return $this->hasMany(OrderItem::class);
	}

}
