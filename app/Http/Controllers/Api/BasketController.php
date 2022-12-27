<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Basket;
use App\Models\Order;
use App\Models\Product;
use App\Models\SizeAndPrice;
use App\Models\User;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    //Отобразить товары пользователя 
    public function index(){
    return Basket::where('user_id', auth()->user()->id)->get();
    }

    //Добавить товар
    public function add(Request $idBasket){
        $attrs = $idBasket->validate([
            'product' => 'required',
            'size' => 'required',
            'amount' => 'required',
            'color' => 'required'
        ]);
        if(!SizeAndPrice::where('product_id',$attrs['product'])->where('size',$attrs['size'])->exists())
            return response([
                'error' => 'Записи не найдено',
            ],200);
        $sale=Product::where('id',$attrs['product'])->first(['sale'])->sale??0;
        $amount = intval($attrs['amount']);
        $price = SizeAndPrice::where('product_id',$attrs['product'])->where('size',$attrs['size'])->value('price');
        // $counter = $amount*($price/100*(100-$sale));

        $basket = Basket::create([
            'user_id' => auth()->user()->id,
            'product_id' => $attrs['product'],
            'vendor_code' => Product::find($attrs['product'])['vendor_code'],//работает
            'sum' => $price,
            'size' => $attrs['size'],
            'sale' => $sale,
            'amount' => $attrs['amount'],
            'color'  => $attrs['color']
        ]);
        
        return response([
            'message' => 'Успешно добавлено',
        ],200);
    }

    //Удалить заказ
    public function delete(Request $idBasket){
        $attrs = $idBasket->validate([
            'basket' => 'required'
        ]);

        $basket = Basket::where('id', $attrs);
        $basketBody = $basket->get();
        
        if($basketBody=='[]'){
            return response([
                'message'=>'запись не найдена'
            ],403);
        }
        else{
            $basket->delete();
            return response([
                'message' => 'Успешно удалено',
            ],200);
    }
    }

        /* //Удаление заказа по id продукта
          $attrs = $idOrder->validate([
            'order' => 'required'
        ]);

        $order = Order::where('product_id', $attrs)->get();
        if($order=='[]'){
            return response([
                'message'=>'запись не найдена'
            ],403);
        } 

        else {
            return response([
                'message'=> $order
            ]);
        }
          */  
    }
