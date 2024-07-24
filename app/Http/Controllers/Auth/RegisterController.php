<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:100',
            'roles' => 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:100',
            'email'=>'required|email',
            'password' =>'required',
        ]);
        if(User::where('email', $request->email)->first()){
            return response([
                'message' => 'Email already exists',
                'status'=>'failed'
            ], 200);
        }
        $user = User::create([
            'roles' => $request->roles,
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        $token = $user->createToken($request->email)->plainTextToken;
        return response([
            'user' => $user,
            'token'=>$token,
            'message' => 'Registration Success',
            'status'=>'success'
        ], 201);
    }
}
