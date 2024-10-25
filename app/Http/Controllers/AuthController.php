<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'min:8'
        ]);


        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        $user->is_admin = $user->roles->pluck('name')->contains('Admin');

        $user->makeHidden(['roles']);


        return response()->json(['token' => $token, 'user' => $user]);
    }


    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out!',
        ]);
    }

    
}
