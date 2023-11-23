<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $log = auth()->attempt($credentials);
        if (!$log) {
            return response()->json(['message' => 'Please Check again your username or password!'], 401);
        }
        $user = User::where('username', $request->username)->orWhere('email', $request->username)->first();
        // $request->user()->tokens()->delete();
        $token = $user->createToken('web-blog-token')->plainTextToken;
        return response()->json(['user' => new UserResource($user), 'token' => $token], 200);
    }


    public function profile(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }


    public function logout(Request $request)
    {
        // $request->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Success logout'], 200);
    }
}
