<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    use ApiResponser;

    public function store()
    {
        if (auth()->user()->is_merchant)
            return $this->error('please create user account', 403);

        try {
            $cart = Cart::create([
                'user_id' => auth()->id(),
                'total' => 0
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
            $cart->items()->attach($request['sku'],['qty'=> $request['qty']]);

            return $this->success('success', $cart);
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage(), 500);
        }
    }

    public function revmoceFromCart(Request $request){

        if (auth()->user()->is_merchant)
            return $this->error('please create user account', 403);
        try {
            $cart = Cart::where('user_id', '=', auth()->id())->first();
            $cart->items()->detach($request['sku']);
            return $this->success('success');
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage(), 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
