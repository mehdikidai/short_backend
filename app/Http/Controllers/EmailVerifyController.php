<?php

namespace App\Http\Controllers;

use App\Jobs\AddUserSheetDb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EmailVerifyController extends Controller
{
    
    
    
    public function __invoke(Request $request)
    {

        $data = $request->validate([
            'verification_code' => 'required'
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }


        $is_equal = $user->verification_code ==  $data['verification_code'];

        if (!$is_equal) {

            return response()->json(['message' => 'try again'], 422);
        }


        $user->markEmailAsVerified();

        Cache::forget('user_' . $user->id);

        AddUserSheetDb::dispatch($user);

        $user->verification_code = mt_rand(100000, 999999);

        $user->save();


        return response()->json(['message' => 'email verified successfully'], 200);
        
    }
}
