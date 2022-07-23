<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
	        'name' => 'Test Customer',
	        'email' => 'test@customer.com',
	        'password' => Hash::make('password'),
        ]);
    }
}
