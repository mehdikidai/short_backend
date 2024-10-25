<?php

namespace Database\Seeders;

use App\Models\Url;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Click;
use App\Models\RoleUser;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([RoleSeeder::class]);

        $userRole = Role::where('name', 'User')->first();

        User::factory()->create([
            'name' => 'mehdi kidai',
            'email' => 'mehdikidai@gmail.com',
            'password' => Hash::make('12345678'),
            'verification_code' => mt_rand(100000, 999999)
        ]);

        User::factory(19)->create()->each(function ($user) use ($userRole) {
            $user->roles()->attach($userRole->id);
        });

        Url::factory(300)->create();

        Click::factory(1000)->create();

        RoleUser::insert([
            [
                'role_id' => 1,
                'user_id' => 1
            ],
            [
                'role_id' => 2,
                'user_id' => 1
            ],
        ]);
    }
}
