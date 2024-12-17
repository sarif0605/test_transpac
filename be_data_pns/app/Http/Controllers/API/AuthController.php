<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Email atau password salah'
            ], 401);
        }
        $userData = User::where('email', $data['email'])->first();
        $token = JWTAuth::fromUser($userData);
        return response()->json([
            "message" => "Login Berhasil",
            "user" => new UserResource($userData),
            "token" => $token
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
