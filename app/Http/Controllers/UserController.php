<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Traits\CodeVerification;

class UserController extends Controller
{


    use CodeVerification;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required|min:3|max:20'
        ]);

        $v_code = $this->make_verification_code();

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'name' => $data['name'],
            'verification_code' => $v_code,
        ]);

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        SendEmailJob::dispatch($data['email'], $v_code, $data['name']);

        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $user  = $request->user();

        $data = $request->validate([
            'name' => 'required|min:3|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);


        if ($user->email !== $data['email']) {

            $v_code = $this->make_verification_code();
            $user->email_verified_at = null;
            $user->verification_code = $v_code;
            SendEmailJob::dispatch($data['email'], $v_code, $data['name']);
        }

        $user->update($data);

        Cache::forget('user_' . $user->id);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function user(Request $request)
    {

        $userId = $request->user()->id;

        $user = Cache::remember('user_' . $userId, 60 * 5, function () use ($request) {

            return $request->user();
        });


        return response()->json($user);
    }
}
