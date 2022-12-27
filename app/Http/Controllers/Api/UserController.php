<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function update(Request $request){
        try{
        function getTable(String $table)
        {
            $user = User::find(auth()->user()->id);
            return $user->where('id',auth()->user()->id)->value($table);
        }

        User::find(auth()->user()->id)->update([
            'name'=> $request['name'] ?? getTable('name'),
            'surname'=> $request['surname']??getTable('surname'),
            'patronymic'=> $request['patronymic']??getTable('patronymic'),
            'email'=> $request['email']??getTable('email'),
            'number_phone'=> $request['number_phone']??getTable('number_phone'),
            'name_organisation'=> $request['name_organisation']??getTable('name_organisation'),
            'address'=> $request['address']??getTable('address'),
        ]);
        return response([
            'succes' => 'Успешно',
        ]);
        }catch(\Throwable $tr){
            return response([
                'error' => 'На сервере произошла ошибка, попробуйте позже',
                'error_log' => $tr,
            ]);
        }
    }
}
