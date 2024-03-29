<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('order_id');
			$table->unsignedBigInteger('product_id')->nullable();
			$table->integer('quantity')->default(0);
	        $table->decimal('unit_price', 10)->default(0);
	        $table->decimal('total', 10)->default(0);
	        $table->timestamps();

	        $table->foreign('order_id')
		        ->references('id')
		        ->on('orders')
		        ->cascadeOnDelete();

	        $table->foreign('product_id')
		        ->references('id')
		        ->on('products')
		        ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
