<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Validar el registro
        $data = $request->validate();
    }

    public function login(LoginRequest $request)
    {
        // return response()->json([
        //     "data" => "hola"
        // ]);
        // exit;
        $data = $request->validated();
        if(!Auth::attempt($data)){
            return response()->json([
                'message' => 'Auth failed.',
                'errors' => [
                    'El email o el password son incorrectos'
                ]
            ], 422);
        }

        $user = Auth::user();
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'data' => $user
        ];
    }

    public function logout()
    {

    }
}
