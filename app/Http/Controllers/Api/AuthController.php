<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    // something............
    // 🔑 Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid credentials',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    // 👤 Current User
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    // 🚪 Logout
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out']);
    }

    // 🔄 Refresh Token
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
