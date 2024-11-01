<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Traits\CodeVerification;
use App\Jobs\DeleteUnverifiedUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

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

        DeleteUnverifiedUsers::dispatch($user->id)->delay(Carbon::now()->addHours(24));

        $userRole = Role::where('name', 'User')->first();

        $user->roles()->attach($userRole->id);

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


        if ($user->email !== $data['email'] && !is_null($user->email_verified_at)) {

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

        $user = User::findOrFail($id);

        Gate::authorize('delete-user', $user);

        $user->delete();

        return response()->json(['message' => 'user deleted'], 200);
    }

    //---------------------------------

    public function user(Request $request)
    {

        $userId = $request->user()->id;

        $user = Cache::remember('user_' . $userId, 60 * 5, function () use ($request) {

            $user = $request->user();
            $user['is_admin'] = $user->roles()->where('name', 'admin')->exists();

            return $user;
        });


        return response()->json($user);
    }


    //---------------------------------


    public function users()
    {

        $users = User::with(['roles:id,name'])->latest()->paginate(6);

        $users->data = collect($users->items())->map(function ($user) {

            $user->is_admin = $user->roles->pluck('name')->contains('Admin');

            $user->makeHidden(['roles', 'email_verified_at']);

            return $user;
        });

        return $users;

        return response()->json($users);
    }


    // update user password ===============



    public function updatePassword(Request $request)
    {

        $data = $request->validate([
            "currentPassword" => "required|min:8|max:25",
            "newPassword" => "required|min:8|max:25|different:currentPassword",
        ]);

        $user = $request->user();

        $old_pass = $user->password;

        if (!Hash::check($data['currentPassword'], $old_pass)) {

            return response()->json(['message' => 'password ghalt'], 400);
        }

        $user->password = Hash::make($data["newPassword"]);

        $res = $user->save();

        return response()->json(["updated" => $res], 201);
    }


    // update user password ===============

    // delete account =====================

    public function delete_account(Request $request)
    {


        $data = $request->validate(['password' => 'required|min:8']);

        $user = $request->user();

        if (!Hash::check($data['password'], $user->password)) return response()->json($data, 400);

        Gate::authorize('delete_account',$user);

        $user->delete();
        
        return response()->json($data, 200);


    }
}
