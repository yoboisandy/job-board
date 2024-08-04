<?php

namespace App\Http\Controllers;

use App\Helpers\APIHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'is_employer' => 'nullable|boolean',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return APIHelper::success('User registered successfully', $user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);
        }

        $user = auth()->user();

        $token = $user->createToken('token')->plainTextToken;

        return APIHelper::success('User logged in successfully', [
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return APIHelper::success('User logged out successfully');
    }

    public function getMe(Request $request)
    {
        return APIHelper::success('User details', $request->user());
    }
}
