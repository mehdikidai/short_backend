<?php

namespace Database\Seeders;

use App\Models\Click;
use App\Models\Url;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'mehdi kidai',
            'email' => 'mehdikidai@gmail.com',
            'password' => Hash::make('12345678'),
            'verification_code' => mt_rand(100000, 999999)
        ]);

        Url::factory(10)->create();

        Click::factory(1000)->create();
        
    }
}
