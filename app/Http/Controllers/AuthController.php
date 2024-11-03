<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailResetPassword;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

    public function sendResetCodeEmail(Request $request)
    {

        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json(['message' => 'Email not found'], 400);


        $code = Str::lower(Str::random(6));

        Cache::put('password_reset_' . $request->email, $code, now()->addMinutes(10));

        try {

            SendEmailResetPassword::dispatch($user->email, $code, $user->name);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to send reset code, please try again'], 500);
        }


        return response()->json(['message' => 'Code Sent']);
        
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|min:8',
        ]);

        $cachedCode = Cache::get('password_reset_' . $request->email);

        if (!$cachedCode || $cachedCode !== $request->code) {
            return response()->json(['message' => 'الرمز غير صحيح أو انتهت صلاحيته.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        Cache::forget('password_reset_' . $request->email);

        return response()->json(['message' => 'تم تحديث كلمة المرور بنجاح.']);
    }
}
