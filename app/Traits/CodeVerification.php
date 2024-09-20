<?php

namespace App\Traits;

trait CodeVerification
{
    public function make_verification_code()
    {
        return mt_rand(100000, 999999);
    }
}
