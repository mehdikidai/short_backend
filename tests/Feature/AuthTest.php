<?php

use App\Models\User;
use App\Helpers\UserHelper as Helper;
use App\Models\Role;
use App\Models\RoleUser;

describe('TEST AUTH', function () {

    // test login - success ---------------------------

    it('test login', function () {


        [$user] = Helper::createAuthenticatedUser();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);

        $user->forceDelete();
    });

    // test login - failed ---------------------------

    it('test login - fail', function () {

        [$user] = Helper::createAuthenticatedUser();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => '1234567888'
        ]);

        $response->assertStatus(401);

        $user->forceDelete();
    });

    // test login - success ---------------------------

    it('test logout - successfully', function () {


        [$user, $token] = Helper::createAuthenticatedUser();


        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/logout');


        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out!']);

        $user->forceDelete();
    });

    // test logout - fail ----------------------------------

    it('test logout - fail - middleware - auth', function () {

        $response = $this->postJson('/api/logout');
        $response->assertStatus(401);
    });

    // password reset - send code --------------------------


    it('password reset - send code', function () {



        [$user] = Helper::createAuthenticatedUser();

        $res = $this->postJson('/api/password/send-reset-code', [
            'email' => $user->email
        ]);

        $res->assertStatus(200);

        $user->forceDelete();
    });


    // password reset - Check for invalid code ----------------


    it('password reset - Check for invalid code', function () {

        [$user] = Helper::createAuthenticatedUser();

        $res = $this->postJson('/api/password/reset', [
            'email' => $user->email,
            'code' => '123456',
            'password' => '12345678@pass',
        ]);

        $res->assertStatus(400);

        $user->forceDelete();
    });

    //----------- remove user account - admin

    it('Remove user account - admin', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $userTwo = User::factory()->create();

        $adminRole = Role::where('name', 'Admin')->first(); 

        RoleUser::create([
            'user_id' => $user->id,
            'role_id' => $adminRole->id
        ]);
    
        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson('/api/user/'.$userTwo->id);
    
        $response->assertStatus(200);
    
        $user->forceDelete();
        
        $userTwo->forceDelete();


    });

    //----------- remove user account - user

    it('Remove user account - user', function () {


        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson('/api/user/account',[
            'password' => 'password'
        ]);
    
        $response->assertStatus(200);

        $user->forceDelete();


    });

    // -------------- User deletes another user

    it('Admin - Account cannot be deleted.', function () {


        [$user, $token] = Helper::createAuthenticatedUser();

        $adminRole = Role::where('name', 'Admin')->first(); 

        RoleUser::create([
            'user_id' => $user->id,
            'role_id' => $adminRole->id
        ]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson('/api/user/account',[
            'password' => 'password'
        ]);
    
        $response->assertStatus(403);
    
        $user->forceDelete();

    });

    // ------------------- change password

    it('Change password', function () {


        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->putJson('/api/user/update-password',[
            "currentPassword" => "password",
            "newPassword" => "12345678",
        ]);
    
        $response->assertStatus(201);
        $user->forceDelete();


    });

    // ------------------- change password - password not correct

    it('Change password - Password not correct', function () {


        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->putJson('/api/user/update-password',[
            "currentPassword" => "password111",
            "newPassword" => "12345678",
        ]);
    
        $response->assertStatus(400);
        $user->forceDelete();
        

    });

    // -------------------- get all users - admin

    it('Get all users - admin - authorized', function () {


        [$user, $token] = Helper::createAuthenticatedUser();

        $adminRole = Role::where('name', 'Admin')->first(); 

        RoleUser::create([
            'user_id' => $user->id,
            'role_id' => $adminRole->id
        ]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/user/users');
    
        $response->assertStatus(200);
    
        $user->forceDelete();


    });

    // -------------------- get all users - admin

    it('Get all users - user - not authorized', function () {


        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/user/users');
    
        $response->assertStatus(403);
    
        $user->forceDelete();
        

    });




    

})->group('auth');
