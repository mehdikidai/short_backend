<?php

use Illuminate\Support\Facades\Cache;


describe('TEST CACHE ', function () {

    $name = 'mehdi_kidai';

    // -------- Store value in cash

    it('Store value in cash', function () use ($name) {

        Cache::put('admin_1', $name, 60);

        $this->assertEquals('mehdi_kidai', Cache::get('admin_1'));

        Cache::forget('admin_1');
    });

    //------------------------

    // -------- Cash Expiry 1s

    it('Cash Expiry 1s', function () use ($name) {

        Cache::put('admin_2', $name, 1);

        sleep(2);

        $this->assertNull(Cache::get('admin_2'));
    });

    //------------------------



})->group('cache');
