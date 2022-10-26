<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoresController extends Controller
{
    use ApiResponser;

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_merchant)
            return $this->error('not allowed', 403);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'url' => 'required',
            'shipping_type' =>'required',
            'shipping_fees'=> 'required',
            'vat_value' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error.', 400, $validator->errors());
        }

        try {
            $store = Store::create([
                'name' => $request['name'],
                'url' => str_replace(' ', '', $request['url']),
                'merchant_id' => Auth()->id(),
                'shipping_type' => $request['shipping_type'],
                'shipping_fees'=>  $request['shipping_fees'],
                'vat_value' => $request['vat_value']
            ]);

            return $this->success('success', $store);

        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage(), 500);
        }

    }

    public function show($url)
    {
        if($url){
            $store = Store::where('url','=',$url)->where('merchant_id','=',auth()->id())->get();
            if(count($store)){
                return $this->success('success',$store);
            }
        }
        return  $this->error('Failed',500);
    }


    public function update(Request $request, $url)
    {
        $store = Store::where('url','=',$url)->first();
        if($store->merchant_id != auth()->id()){
            return $this->error('not allowed', 403);
        }
        return $store->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }




}
