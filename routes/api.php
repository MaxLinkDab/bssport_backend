<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\backendApiController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Отображение продукции
Route::apiResources(['product'=>ProductController::class]); //Манипуляции с продукцией
Route::get("product/search/{name}",[ProductController::class,'search']);



//Автроизация
Route::post('registration',[AuthController::class,'registration']);//Регистрация   
Route::post('login',[AuthController::class,'login']);//Логин

/**
 * Регистрация в интернет-магазине:
 * Для того чтобы зарегистрироваться в интернет-магазине требуется естественно зарегестрироваться и получить токен, для дальнейшей пребывания
 * в интернет-магазине, для этого вбейте в адресной строке вот эту строку:
 * http://127.0.0.1:8000/api/registration?name=Максим&email=maxlink556@gmail.com&password=МаксимПасс&password_confirmation=МаксимПасс 
 * создастся аккаунт с именем "Максим", почтой "maxlink556@gmail.com", паролем "МаксимПасс".
 * Ниже вы получите токен.
 * 
 * 
 * Вход в интернет-магазин:
 * Перейдите по адресу: 
 * http://127.0.0.1:8000/api/login?email=maxlink556@gmail.com&password=56695683968
 * и не забудьте скопировать токен для дальнейшей работы.
 * 
 * 
 * Выход из интернет-магазина:
 * http://127.0.0.1:8000/api/logout
 * и все, вы вышли!
 * */ 


Route::group(['middleware'=>['auth:sanctum']], function(){

    //Заказы
    Route::get('order',[OrderController::class,'index']);//Показать заказы
    Route::post('order/create',[OrderController::class,'add']);//Добавить заказы
    Route::post('order/delete',[OrderController::class,'delete']);//Удалить заказы
    
    /**
     * Просмотр заказов:
     * Для того чтобы посмотреть заказы, нужно авторизироваться, получить токен.
     * Далее выбрать тип авторизации "Bearer Token" и вставить полученный токен. Вуаля, у вас есть собственная корзина.
     * Теперь нужно передти на этот адрес:
     * http://127.0.0.1:8000/api/order/
     * 
     * 
     * 
     * Добавление заказа:
     * Чтобы создать заказ, нужно вбить в адресную строку этот путь:
     * http://127.0.0.1:8000/api/order/create?product=10, где "product=10", указываем id продукта который хотим добавить в корзину.
     * 
     * 
     * Удаление заказа:
     * Чтобы удалить заказ, нужно вбить в адресную строку этот путь:
     * http://127.0.0.1:8000/api/order/delete/7, где "7" удаляет ID ЗАКАЗА, а не заказ с id продукта!!! с номером 7,
     * но в скором времени этот путь может измениться.
     * 
     */
    
    //Разлогин
    Route::get('user',[AuthController::class, 'user']); 
    Route::post('logout',[AuthController::class, 'logout']); //Разлогин
});





