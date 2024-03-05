<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

   public function register(Request $request) {

       $validator = Validator::make($request->all(), [

           'name' => ['required','string','min:3'],
           'username' => ['required','string','min:3'],
           'email' => ['required','string','email','unique:users,email,except,id'],
           'password' => ['required']

       ]);

       if ($validator->fails()) {

           return  response(['error'=>  $validator->errors()],422);
       }

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

      $user = User::where('email',$request->email)->first();

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


   public function logout(Request $request)
   {
       auth()->user()->tokens()->delete();
       return [
           'message' => 'user logged out'
       ];

   }
}
