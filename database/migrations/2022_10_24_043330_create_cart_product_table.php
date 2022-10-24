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
        Schema::create('cart_product', function (Blueprint $table) {
            $table->id();
            $table->string('product_sku');
            $table->unsignedBigInteger('cart_id');
            $table->unique(['cart_id', 'product_sku']);
            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('carts')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_sku')->references('sku')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_product');
    }
};