<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use ApiResponser;

    public function store(Request $request)
    {
        if (auth()->user()->is_merchant)
            return $this->error('please create user account', 403);

        try {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'total' => 0,
                'store_id' => $request['store_id']
            ]);

            return $this->success('success', $cart);
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage(), 500);
        }
    }

    public function show()
    {
         $cart = Cart::where('user_id', '=', auth()->id())->with(['items'])->first();
        if ($cart) {
            if ($cart->store->shipping_type === 'fixed')
                $cart->final_total = $cart->total + $cart->store->shipping_fees;
            else
                $cart->final_total = ($cart->total) + ($cart->store->shipping_fees * $cart->total / 100);
                $cart->shipping_cost = ($cart->store->shipping_fees * $cart->total / 100);
            return $this->success('success', $cart);
        }
        return  $this->error('Failed', 500);
    }

    public function addToCart(Request $request)
    {
        if (auth()->user()->is_merchant)
            return $this->error('please create user account', 403);
        try {
            $cart = Cart::where('user_id', '=', auth()->id())->first();
            $product = Product::where('sku', '=', $request['sku'])->first();
            if ($cart->store->id != $product->store->id)
                return $this->error('this cart does not belong to this product store', 403);

            $cart->items()->attach($product->sku, ['qty' => $request['qty']]);
            $this->updateTotals($cart, $product, true, $request['qty']);

            return $this->success('success');
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage(), 500);
        }
    }

    public function removeFromCart($sku)
    {

        if (auth()->user()->is_merchant)
            return $this->error('please create user account', 403);
        try {
            $cart = Cart::where('user_id', '=', auth()->id())->first();
            $product = Product::where('sku', '=', $sku)->first();
            $cart->items()->detach($sku);
            $this->updateTotals($cart, $product, false);
            return $this->success('removed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage(), 500);
        }
    }

    private function updateTotals(Cart $cart, Product $product, $add, $qty = 1)
    {
        $store = $product->store;
        $total = $cart->total;
        $price = $product->price;

        if (!$product->tax_included) {
            $price = ($product->price * $store->vat_value / 100) + $product->price;
        }

        if ($add)
            $total = $total + ($price * $qty);
        else
            $total = $total - $price;

        $cart->total = $total;
        $cart->save();
    }

    // public function destroy(Cart $cart)
    // {
    //     //
    // }
}
