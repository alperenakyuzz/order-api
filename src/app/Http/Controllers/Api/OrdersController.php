<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Throwable;

class OrdersController extends Controller
{

	public function __construct(
		protected OrderService $orderService
	)
	{
	}

	/**
     * Display a listing of the resource.
     *
     * @return JsonResponse
	 */
    public function index(): JsonResponse
    {
        return response()->json([
			'orders' => $this->orderService->getAll()
        ]);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param OrderCreateRequest $request
	 *
	 * @return JsonResponse
	 */
    public function store(
	    OrderCreateRequest $request
    ): JsonResponse {
		$order = $this->orderService->create($request->all());

        return response()->json([
			'order_id' => $order->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
	    return response()->json([
		    'order' => $this->orderService->get($id)
	    ]);
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
    public function destroy(int $id): JsonResponse
    {
		if(!$this->orderService->delete($id)) {
			throw new \Exception('An error occurred when delete order');
		}

	    return response()->json([
		    'message' => 'Order deleted'
	    ]);
    }

	/**
	 * Apply discounts to order
	 *
	 * @param int $id
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function applyDiscounts(int $id): JsonResponse
	{
		$order = $this->orderService->get($id);
		$order = $this->orderService->applyDiscounts($order);

		return response()->json([
			'order_id' => $order->id,
			'discounts' => $order->discounts,
			'total_discount' => $order->total_discount,
			'discounted_total' => $order->discounted_total
		]);
	}

}
