<?php

use App\Models\Url;
use App\Models\User;

describe('URL REDIRECT', function () {


    // ------------- Url Redirect - FAIL

    it('UrlRedirect - No Param Code', function () {

        $response = $this->get('/');

        $response->assertStatus(404);
    });

    // ------------- Url Redirect - SUCCESS

    it('Url Redirect - Success', function () {

        $user = User::factory()->create();

        $url = Url::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get('/' . $url->code);

        $response->assertStatus(302);

        $user->delete();
    });


})->group('url_redirect');
