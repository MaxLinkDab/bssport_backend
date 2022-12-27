<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\backendApiController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BasketController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Models\Administrator;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
Route::get("product", [ProductController::class, 'index']); //получить все продукты
Route::get("product/search/{name}", [ProductController::class, 'search']); //Поиск продукции

Route::get('banner', [BannerController::class, 'index']);




//Автроизация
Route::post('registration', [AuthController::class, 'registration']); //Регистрация   
Route::post('login', [AuthController::class, 'login']); //Логин

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


Route::group(['middleware' => ['auth:sanctum']], function () {

    //Заказы
    Route::get('basket', [BasketController::class, 'index']); //Показать заказы
    Route::post('basket/add', [BasketController::class, 'add']); //Добавить заказы
    Route::post('basket/delete', [BasketController::class, 'delete']); //Удалить заказы
    
    Route::get('order', [OrderController::class, 'index']); //Показать заказы
    Route::post('order/add', [OrderController::class, 'create']); //добавить заказы

    Route::post("product/add", [ProductController::class, 'store']);
    Route::post("product/delete", [ProductController::class, 'destroy']); //Удалить продукт
    Route::post("product/update/{idProduct}", [ProductController::class, 'update']); //Обновить продукт

    Route::post('banner/add', [BannerController::class, 'store']);
    Route::post('banner/delete', [BannerController::class, 'destroy']);


    /**
     * Просмотр заказов:
     * Для того чтобы посмотреть заказы, нужно авторизироваться, получить токен.
     * Далее выбрать тип авторизации "Bearer Token" и вставить полученный токен. Вуаля, у вас есть собственная корзина.
     * Теперь нужно передти на этот адрес:
     * http://127.0.0.1:8000/api/basket/
     * 
     * 
     * 
     * Добавление заказа:
     * Чтобы создать заказ, нужно вбить в адресную строку этот путь:
     * http://127.0.0.1:8000/api/basket/add?product=10, где "product=10", указываем id продукта который хотим добавить в корзину.
     * 
     * 
     * Удаление заказа:
     * Чтобы удалить заказ, нужно вбить в адресную строку этот путь:
     * http://127.0.0.1:8000/api/basket/delete?basket=1, где "7" удаляет ID ЗАКАЗА, а не заказ с id продукта!!! с номером 7,
     * но в скором времени этот путь может измениться.
     * 
     */

    //Разлогин
    Route::get('user', [AuthController::class, 'user']);
    Route::post('user/update_info', [UserController::class, 'update']);
    Route::post('logout', [AuthController::class, 'logout']); //Разлогин
});
