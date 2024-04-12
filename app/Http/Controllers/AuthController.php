<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:5'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->errors(),
            ], 422);
        }

        if (Auth::attempt($validatedData)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $accessToken = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Login success',
                'user' => $user,
                'accessToken' => $accessToken,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Email or password incorrect'
            ], 401);
        }
    }

    public function logout(Request $request) {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logout Success'
            ], 200);        
    }
}
