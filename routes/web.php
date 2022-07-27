<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/orders', function(){
    $users = \App\Models\User::all();
    foreach($users as $user) {
         echo 'users name: '.$user['name'].'<br>';
         echo '<b>user\'s products: </b><br>';
         foreach ($user->products as $product) {
            echo $product['name'].','.'<br>';
        }
         echo '<br>';
         echo '----------------------------------'.'<br>';
    }

});
Route::get('/size', function(){
    $sizeUnparse = '100-150';
    $size=[];
    $sizeList = list($minSize, $maxSize) = explode('-', $sizeUnparse);
    echo($maxSize);
    for ($i = $minSize; $i <= $maxSize; $i += 5) {
         echo($i);
         echo('<br>');
        //  break;
        //  if($i<=150) return 0;
    }
});
