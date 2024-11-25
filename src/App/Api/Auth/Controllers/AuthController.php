<?php

namespace App\Api\Auth\Controllers;

use Domain\Users\DataTransferObjects\LoginUserData;
use Domain\Users\DataTransferObjects\RegisterUserData;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Support\Controllers\Controller;

class AuthController extends Controller
{
    public function login(LoginUserData $loginUserData, Request $request): JsonResponse
    {
        if (!Auth::attempt($loginUserData->toArray())) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $request->user()->createToken('app')->plainTextToken,
        ]);
    }

    public function register(RegisterUserData $registerUserData, Request $request): JsonResponse
    {
        $validated = $registerUserData->toArray();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'token' => $user->createToken('app')->plainTextToken,
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
