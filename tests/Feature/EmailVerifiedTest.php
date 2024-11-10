<?php

use App\Helpers\UserHelper as Helper;

describe('TEST EMAIL', function () {


    // Email Verified - Code Correct -------------------------

    it('Email Verified - Code Correct', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $user->email_verified_at = null;
        $user->save();

        $verificationCode = $user->verification_code;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/email/verify', [
            'verification_code' => $verificationCode
        ]);

        $response->assertStatus(200);

        $user->forceDelete();
    });

    // Email Verified - Code Not Correct -------------------------

    it('Email Verified - Code Not Correct', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $user->email_verified_at = null;
        $user->save();

        $verificationCode = 123456;

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/email/verify', [
            'verification_code' => $verificationCode
        ]);

        $response->assertStatus(422);

        $user->forceDelete();
    });
    
})->group('email');
