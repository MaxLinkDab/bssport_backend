<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Order;
use App\Models\Product;
use App\Models\SizeAndPrice;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    public function index()
    {
        return  Order::where('user_id', auth()->user()->id)->get();
    }

    public function create(Request $request)
    {
        $attrs = $request->validate([
            'basket' =>  'required'
        ]);



        for ($i = 0; $i < count($attrs['basket']); $i++) {
            if (is_null(Basket::where('id', $attrs['basket'][$i])->first())) {
                return response(['error' => 'Корзина ' . $attrs['basket'][$i] . ' не найдена'], 500);
            }
        }
        $user = User::find(auth()->user()->id);
        if (empty($user->value('number_phone')) || empty($user->value('address')) || empty($user->value('surname'))) {
            return response([
                'error' => 'Недостаточно данных пользователя, обязательны поля телефон, адрес и фамилия'
            ], 404);
        }
        $sum = [];
        foreach ($request['basket'] as $basketId) {
            $basket = Basket::find($basketId);
            $productId = $basket->product_id;
            $sale = $basket->sale ?? 0;
            $price = $basket->sum;
            $amount = $basket->amount;
            array_push($sum, $amount * ($price / 100 * (100 - $sale)));
        }
        $orderSum = array_sum($sum); // сумма заказа


        for ($i = 0; $i < count($attrs['basket']); $i++) {
            $order = Order::create([
                'user_id'   => auth()->user()->id,
                'baskets_id' => $attrs['basket'][$i],
                'sum'       => $orderSum,
                'name'      => User::find(auth()->user()->id)->value('name'),
                'surname'      => User::find(auth()->user()->id)->value('surname'),
                'number_phone'      => User::find(auth()->user()->id)->value('number_phone'),
                'address'      => User::find(auth()->user()->id)->value('address'),
                'status'    => 'Не оплачен',
            ]);
        }

        //удалять заказанные продукты из корзины (пока не надо)
        //сделать перенос данных пользователя (имя, фамилия, номер и адрес и тд в строку orders), если их нету, предложить пользователю заполнить данные через отдельный запрос, который обновляет информацию пользователя 
        return response(['succes' => 'Успешно добавлено!'], 200);
    }
    function update(Request $request)
    {
    }
}
