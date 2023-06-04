<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateUserDataRequest;


class AuthController extends Controller
{
    public function getAllUsers()
    {
        try {
            $users = User::all();

            $usersResource = UserResource::collection($users);
            return response()->json([
                'status' => 200,
                'message' => 'Users retrieved successfully',
                'data' => [
                    'users' => $usersResource,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function updateUserData(UpdateUserDataRequest $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found',
                ], 404);
            }
            
            $is_admin = $request->is_admin ? 1 : 0;

            // Update user data based on the validated request
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->is_admin = $is_admin;

            if ($request->hasFile('image')) {
                $oldImage = $user->image;

                // Store the new image
                $imagePath = $request->file('image')->store('public/images');
                $user->image = $imagePath;

                // Delete the old image file
                if ($oldImage && Storage::exists($oldImage)) {
                    Storage::delete($oldImage);
                }
            }
            $user->save();

            $userResource = new UserResource($user);
            return response()->json([
                'status' => 200,
                'message' => 'User data updated successfully',
                'data' => [
                    'user' => $userResource,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            // Store the new image
            $imagePath = $request->file('image')->store('public/images');
            $is_admin = $request->is_admin ? 1 : 0;
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'image' => $imagePath,
                'is_admin' => $is_admin,
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
