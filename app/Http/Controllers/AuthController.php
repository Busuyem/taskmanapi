<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Hash;
use Throwable;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = Hash::make($request->password);
            $createdUser = User::create($validatedData);
            $token = $createdUser->createToken('Authentication Token for '. $createdUser->email)->plainTextToken;
            return response()->json([
                'status_code' => 201,
                'message' => 'success!',
                'token' => $token
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'The provided credentials do not match.'
                ], 401);
            } else {
                $token = $user->createToken('Token')->plainTextToken;
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Login successful!',
                    'token' => $token
                ], 200);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
            return response()->json([
                'message' => 'Logged out successfully!'
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status_code' => 500,
                'error' => $e->getMessage(),
                'message' => "Logout failed"
            ], 500);
        }
    }
}
