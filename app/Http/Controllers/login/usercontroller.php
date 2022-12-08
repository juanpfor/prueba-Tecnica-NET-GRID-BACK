<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Responsables\EstructuraApi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class usercontroller extends Controller
{
   public function register(Request $request)
   {
      $api = new EstructuraApi();

      $validations = Validator::make($request->all(), [
         'name' => 'required',
         'email' => 'required|email|unique:users',
         'address' => 'required',
         'password' => 'required|confirmed',
         'birthdate' => 'required',
         'city' => 'required'
      ]);

      if ($validations->fails()) {
         $api->setEstado(200, 'error', $validations->errors());
         return $api->toResponse(null);
      }

      $user = new  User();
      $user->name  = $request->name;
      $user->email  = $request->email;
      $user->address  = $request->address;
      $user->password  = Hash::make($request->password);
      $user->birthdate  = $request->birthdate;
      $user->city  = $request->city;
      $user->description  = $request->description;

      $user->save();

      $api->setResultado($request->all());
      return $api->toResponse(null);
   }
   public function login(Request $request)
   {
      $api = new EstructuraApi();

      $validations = Validator::make($request->all(), [
         'email' => 'required|email',
         'password' => 'required',
      ]);

      if ($validations->fails()) {
         $api->setEstado(200, 'error', $validations->errors());
         return $api->toResponse(null);
      }

      $user = User::where('email', $request->email)->first();

      if (isset($user)) {
         if (!Hash::check($request->password, $user->password)) {

            $api->setEstado('204', 'error', "password or email incorrect");
            return $api->toResponse(null);
         }
         //create token by user
         $token = $user->createToken("auth_token")->plainTextToken;
         $api->setResultado(["token" => $token]);
         return $api->toResponse(null);
      }

      $api->setEstado('204', 'error', "usuario no registrado");
      return $api->toResponse(null);
   }
   public function userProfile()
   {
      $api = new EstructuraApi();
      $api->setResultado(auth()->user());
      return $api->toResponse(null);

   }
   public function logout()
   {
      $api = new EstructuraApi();

      Auth::user()->tokens()->delete();

      $api->setEstado('200', 'success', "logout exitoso");
      return $api->toResponse(null);
   }
}
