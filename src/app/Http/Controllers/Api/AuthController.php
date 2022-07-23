<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(
		LoginRequest $request,
    ): JsonResponse
    {
		$data = $request->all();
	    $customer = Customer::where('email', $data['email'])
		    ->first();

	    if (!$customer || !Hash::check($data['password'], $customer->password)) {
		    throw ValidationException::withMessages([
			    'email' => ['Email or password is not valid'],
		    ]);
	    }

		return response()->json([
			'token' => $customer->createToken('API Token')->plainTextToken
		]);
    }
}
