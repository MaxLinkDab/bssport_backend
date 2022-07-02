<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    //Регистрация пользователя
    public function registration(Request $request){
        $attrs = $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name'=> $attrs['name'],
            'email'=>$attrs['email'],
            'password'=>bcrypt($attrs['password']),
        ]);

        return response([
            'user'=>$user,
            'token'=>$user->createToken('secret')->plainTextToken
        ]);
    }

    //Логин пользователя
    public function login(Request $request){

        $attrs = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);

        if(!Auth::attempt($attrs)){
            return response([
                'message'=>'неверные данные пользователя'
            ], 403);
        }

        return response([
            'user'=>$request->user(),
            'token'=>$request->user()->createToken('secret')->plainTextToken
        ],200);
    }

    //Логаут пользователя
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response([
            'message'=>'Выход успешен'
        ],200);
    }

    //получить информацию о пользователе
    public function user()
    {
        return response([
            'user'=>auth()->user()
        ],200);
    }
}
