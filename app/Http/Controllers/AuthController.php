<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            // Store the new image
            $imagePath = $request->file('image')->store('public/images');

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'image' => $imagePath,
            ]);
            $user->save();
            $token = $user->createToken('access_token')->plainTextToken;

            $userResource = new UserResource($user);
            return response()->json([
                'status' => 200,
                'message' => 'Registration successful',
                'data' => [
                    'user' => $userResource,
                    'token' => $token,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid credentials',
                ], 401);
            }


            $user = $request->user();
            $token = $user->createToken('access_token')->plainTextToken;

            $userResource = new UserResource($user);

            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'data' => [
                    'user' => $userResource,
                    'token' => $token,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found',
                ], 404);
            }

            // Check if the old password matches the user's current password
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Old password is incorrect',
                ], 400);
            }

            // Update the user's password with the new one
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'Password reset successful',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated',
            ], 401);
        }

        try {
            $user->tokens()->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Logout successful',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
