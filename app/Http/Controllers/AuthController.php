<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        User::create($data);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
        ], 201);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
        ]);
    
    }

    public function profile(){
        $user = Auth::user();
        return response()->json([
            'status' => true,
            'message' => "User profile retrieved successfully",
            'user' => $user
        ]);
    }

    public function logout(){

        Auth::logout();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ]);
    }
}
