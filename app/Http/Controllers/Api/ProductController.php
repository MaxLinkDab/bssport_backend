<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\SizeAndPrice;
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


        $attrs = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required',
            'price_kid' => 'required',
            'growth' => 'required',
            'growth_kid' => 'required',
            'color' => 'required|string',
            'material' => 'required|string',
            'gender' => 'required|string',
            'kid' => 'required'

        ]);
        $filename = date('H.i_d.m.y') . '.jpg';
        $path = $request->file('photo')->move(public_path("storage/image/"), $filename);
        $photo = url('storage/image/' . $filename);




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

        
        list($minGrowth, $maxGrowth) = explode('-', $attrs['growth']);
        for ($i = $minGrowth; $i <= $maxGrowth; $i += 5) {
            // $growth = $i;
            SizeAndPrice::create([
                'product_id' => $created_product['id'],
                'size' => $i,
                'price' => $attrs['price'],
            ]);
        }

        list($minGrowthKid, $maxGrowthKid) = explode('-', $attrs['growth_kid']);
        for ($i = $minGrowthKid; $i <= $maxGrowthKid; $i += 5) {
            // $growth = $i;
            SizeAndPrice::create([
                'product_id' => $created_product['id'],
                'size' => $i,
                'price' => $attrs['price_kid'],
            ]);
        }

        


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

    public function search($name)
    {
        return Product::where("name", "like", "%" . $name . "%")->get();
    }
}
