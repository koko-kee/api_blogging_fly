<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

   public function register(RegisterRequest $request) {

      $request->validated();

      $userData = [

         'name' => $request->name,
         'username' => $request->username,
         'email' => $request->email,
         'password' => Hash::make($request->password)

      ];

      $user = User::create($userData);
      $token = $user->createToken('api')->plainTextToken;
      return response(['user' => $user, 'token' => $token],201);
   }

   public function login(loginRequest $request){

     $request->validated();

      $user = User::where('username',$request->username)->first();

      if($user && Hash::check($request->password,$user->password)){

         $token = $user->createToken('api')->plainTextToken;
         return response(
            [
               'user' => $user,
               'token' => $token
            ],
            200
         );

      }
      return response(['message' => 'email ou password incorrect'],422);

   }
}
