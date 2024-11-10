<?php

use App\Traits\CodeVerification;

test('verification code is 6 digits', function () {

    class Test
    {
        use CodeVerification;
    }

    $test = new Test();
    $code = $test->make_verification_code();

    expect(strlen($code))->toBe(6);
    expect(is_numeric($code))->toBeTrue();

});
