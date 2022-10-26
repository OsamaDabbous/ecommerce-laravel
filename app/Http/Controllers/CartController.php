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
            $cart->shipping_cost = number_format((float)$cart->final_total - $cart->total, 2, '.', '');
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

            $cart->items()->attach($product->id, ['qty' => $request['qty']]);
            $this->calculateTotal($cart);
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
            $cart->items()->detach($product->id);
            $this->calculateTotal($cart);
            return $this->success('removed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage(), 500);
        }
    }

    private function calculateTotal(Cart $cart)
    {
        $total = 0;
        $items = $cart->items;
        $storeVAT = $cart->store->vat_value;
        foreach ($items as $item) {
            if ($item->tax_included) {
                $total = $total + ($item->price * $item->pivot->qty);
            } else {
                $total = $total + (($item->price + ($item->price * $storeVAT / 100))*$item->pivot->qty);
            }
        }
        $cart->total = $total;
        $cart->save();
    }
}
