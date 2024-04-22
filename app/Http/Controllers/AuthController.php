<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
  public function register(RegisterRequest $request)
  {
    $data = $request->validated();

    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => bcrypt($data['password']),
    ]);

    return [
      'token' => $user->createToken('token')->plainTextToken,
      'user' => $user,
    ];
  }

  public function login(LoginRequest $request)
  {
    $data = $request->validated();

    if (!Auth::attempt($data)) {
      return response([
        'errors' => ['Las credenciales no coinciden con nuestros registros']
      ], 422);
    }

    //Authenticar al usuario
    $user = Auth::user();
    return [
      'token' => $user->createToken('token')->plainTextToken,
      'user' => $user,
    ];
  }

  public function logout(Request $request)
  {
  }
}
