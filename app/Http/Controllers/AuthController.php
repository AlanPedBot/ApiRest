<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->all('email', 'password');

        $token = auth('api')->attempt($credentials);
        //usuario autenticado
        if ($token) {
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['erro' => 'Usuário ou senha invalidos'], 403);
        }
        return 'login';
    }
    public function logout()
    {
        return 'logout';
    }
    public function refresh()
    {
        return 'refresh';
    }
    public function me()
    {
        return response()->json(auth()->user());
    }
}
