<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LDAP\Result;

class ProductController extends Controller
{
    
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    
     
    public function store(ProductStoreRequest $request)
    {
        $attrs = $request->validated([
            'name' => 'required|string',
            'description' => 'required|string',
            // 'photo',
            'price' => 'required',
            'price_kid' => 'required',
            'growth' => 'required',
            'color' => 'required|string',
            'material' => 'required|string',
            'gender' => 'required|string',
            'kid' => 'required'
            
        ]);
        $photo = $this->prouctsImage($request->photo);

        $created_product = Product::create([
            'name' => $attrs['name'],
            'description' => $attrs['description'],
            'photo' => $photo,
            'price' => $attrs['price'],
            'price_kid' => $attrs['price_kid'],
            'growth' => $attrs['growth'],
            'color' => $attrs['color'],
            'material' => $attrs['material'],
            'gender' => $attrs['gender'],
            'kid' => $attrs['kid'],
        ]);
        return new ProductResource($created_product);
    }

  
     
    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    public function update(ProductStoreRequest $request, Product $product)
    {
        $product->update($request->validated());
        return new ProductResource($product);
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function search($name){
        return Product::where("name","like", "%".$name."%")->get();
    }
}
