<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Http\Resources\ProductResource;
use App\Models\Administrator;
use App\Models\Basket;
use App\Models\Color;
use App\Models\Media;
use App\Models\Order;
use App\Models\Product;
use App\Models\SizeAndPrice;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->all();
        dump($file);
    }
    public function view_orders()
    {
        if(!isset(Auth::user()->name)){
            return view('unauth');
        }
        else if((Administrator::where('email',Auth::user()->email)->value('email')===null)){
            return view('unauth');
        }
        $data['order_info'] = [];
        $id = 0;
        foreach (Order::all() as $order) {
            $id++; 
            $orderId = $order->id;
            $basketId = $order->baskets_id;
            $basket = Basket::find($basketId);
            $productId = Basket::find($basketId)->product_id;
            $product = Product::find($productId);
            $price = SizeAndPrice::where('product_id',$productId)->where('size', $basket->size)->value('price');
            $user = User::find($basket->user_id);
            $media = Media::where('product_id', $productId)->value('media');
            $name = $product->name ?? null;
            $description = $product->description ?? null;
            $amount = $basket->amount ;
            array_push($data['order_info'], [
                'id' => $id,
                'id_order' => $orderId,
                'id_basket' => $basketId,
                'id_product' => $productId,
                'media' => $media,
                'name'  => $name,
                'description'  => $description,
                'price'  => $price,
                'sale'  => $product->sale ?? null,
                'sum'  => $order->sum,
                'amount'  => $amount,
                'color'  => $basket->color,
                'size'  => $basket->size,
                'status' => $order->status,
                'address'  => $user->address,
                'phone_number'  => $user->number_phone,
                'SNP'  => $user->name.' '.$user->surname.' '.$user->patronymic,
            ]);
            /*             $productId = Product::find($basket)->value('id');
            $product = Product::find($basket);
            $productName = $product->value('name');
            $productDescription = $product->value('description');
            $media = Media::where('product_id',$productId)->value('media');
            array_push($data, [$order->id, $productId, $basket,$media  , $productName,$productDescription ]); */
        }
        // return response([$data]);
        return view('orders/view_orders', compact('data'))->with($data['order_info']);
    }

    public function detail($order){
        $data['order_info'] = [];
        (int)$id = 3;
            $order = Order::find($order);
            $orderId = $order->id;
            $basketId = $order->baskets_id;
            $basket = Basket::find($basketId);
            $productId = Basket::find($basketId)->product_id;
            $product = Product::find($productId);
            $price = SizeAndPrice::where('product_id',$productId)->where('size', $basket->size)->value('price');
            $user = User::find($basket->user_id);
            $media = Media::where('product_id', $productId)->value('media');
            $name = $product->name??null;
            $description = $product->description??'null';
            $amount = $basket->amount;
            array_push($data['order_info'], [
                'id' => $id,
                'id_order' => $orderId,
                'id_basket' => $basketId,
                'id_product' => $productId,
                'media' => $media,
                'name'  => $name,
                'description'  => $description,
                'price'  => $price,
                'sale'  => $product->sale??null,
                'sum'  => $order->sum,
                'amount'  => $amount,
                'color'  => $basket->color,
                'size'  => $basket->size,
                'status' => $order->status,
                'address'  => $user->address,
                'phone_number'  => $user->number_phone,
                'SNP'  => $user->name.' '.$user->surname.' '.$user->patronymic,
            ]);
        
        return view('orders/view_orders_detail', compact('data'))->with($data['order_info']);

    }
    public function update_status($order, Request $request){
        $orderObject = Order::find($order);
        $orderObject->update(['status' => $request['status']??$orderObject->status]);
        dd([$request,$orderObject]);
        return redirect('/upload/view_orders/detail/' . $order);
    }
    
    public function product_view(){
        $data=[];
        foreach(Product::all() as $product){
            $product['media']=Media::where('product_id', $product->id)->value('media');
            $priceAndSize = [];
            foreach(SizeAndPrice::where('product_id',$product->price_and_size)->get() as $sizeAndPrices){
                $sizeAndPrice[$sizeAndPrices->size] =  $sizeAndPrices->price;
                $product['size_and_price']=$sizeAndPrice;
            }
            
            foreach(Color::where('product_id', $product->color)->get() as $color){
                $color=$color->color;
                $product['color']=$color;
            }

            array_push($data, $product);
        }
        return view('product/view_product',compact('data'));
    }
    public function edit_product($productId){
        // $data=[];
        $product = Product::find($productId);
        // dd($product->id);
            $product['media']=Media::where('product_id', $product->id)->value('media');
            $size = [];
            $price = [];
            foreach(SizeAndPrice::where('product_id',$product->price_and_size)->get() as $sizeAndPrices){
/*                 $size=$sizeAndPrices->size;
                $price=$sizeAndPrices->price; */
                $size[]=$sizeAndPrices->size;
                $price[]=$sizeAndPrices->price;
                $product['size']=$size;
                $product['price']=$price;
                // $sizeAndPrice[$sizeAndPrices->size] =  $sizeAndPrices->price;
            }
                $colors=[];
            foreach(Color::where('product_id', $product->color)->get('color') as $color){
                $colors['color'][]=$color->color;
                $product['color']=$colors['color'];
            }
            
            $data= $product;
        
        // return response($data);
        // dd($data);
        return view('/product/edit_product', compact('data'))->with('data',$data); 
    }
}
