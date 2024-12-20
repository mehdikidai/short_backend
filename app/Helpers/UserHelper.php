<?php

namespace App\Helpers;

use App\Models\User;

class UserHelper
{

    public static function createAuthenticatedUser(): array
    {
        $user = User::factory()->create();
        $token = $user->createToken('AuthToken')->plainTextToken;
        return [$user, $token];
    }
}
