<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //Отобразить товары пользователя 
    public function index(){
    return Order::where('user_id', auth()->user()->id)->get();
    }

    //Добавить товар
    public function add(Request $idOrder){
        $attrs = $idOrder->validate([
            'product' => 'required'
        ]);
        
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'product_id' => $attrs['product']
        ]);

        return response([
            'message' => 'Успешно добавлено',
        ],200);
    }

    //Удалить заказ
    public function delete(Request $idOrder){
        $attrs = $idOrder->validate([
            'order' => 'required'
        ]);

        $order = Order::where('id', $attrs);
        $orderBody = $order->get();
        
        if($orderBody=='[]'){
            return response([
                'message'=>'запись не найдена'
            ],403);
        }
        else{
            $order->delete();
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
