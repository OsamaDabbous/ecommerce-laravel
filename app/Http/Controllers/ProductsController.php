<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProductsController extends Controller
{

use ApiResponser;

    public function store(Request $request)
    {
        if (!auth()->user()->is_merchant)
            return $this->error('not allowed', 403);

        $validator = Validator::make($request->all(), [
            'name_en' => 'required',
            'name_ar' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
            'price' => 'required',
            'tax_included' => 'required',
            'store_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error.', 400, $validator->errors());
        }
        try {
            $product = Product::create([
                'name_en' => $request['name_en'],
                'name_ar' => $request['name_ar'],
                'description_en' => $request['description_en'],
                'description_ar' => $request['description_ar'],
                'price' => $request['price'],
                'tax_included' => $request['tax_included'],
                'store_id' => $request['store_id'],
                'sku' => $this->generateSKU($request['name_en'])
            ]);

            return $this->success('success', $product);

        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage(), 500);
        }
    }


    public function show($sku)
    {
        try{
            $product = Product::findOrFail($sku);
        }catch(\Illuminate\Database\QueryException $ex){
            return $this->error($ex->getMessage(), 500);
        }
    }

    public function getProductsListBystore($url){
        if($url){
            $store = Store::where('url','=',$url)->first();
            if($store){
                return $this->success('success',$store->products);
            }
        }
        return  $this->error('Failed',500);
    }

    private function generateSKU($name){
        return substr(str_replace(array('\'', '"', ',', ';', '<', '>', '$', '.', '|', '/'), '', Hash::make($name . Carbon::now()->toDateString())), 0, 10);
    }
}
