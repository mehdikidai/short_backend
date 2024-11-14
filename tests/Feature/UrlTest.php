<?php

use App\Models\Url;
use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\UserHelper as Helper;



describe('TEST URLS', function () {

    it('Get all urls not login', function () {

        $response = $this->getJson('/api/urls');

        $response->assertStatus(401);
    });

    // ------------------- get all urls

    it('Get all urls', function () {


        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/urls');

        $response->assertStatus(200);

        $user->forceDelete();
    });

    //-------------------- create new short link


    it('Create new short link', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->postJson('/api/urls', [
            'original_url' => 'https://playcode.io',
            'title' => 'test url',
        ]);


        $response->assertStatus(201);

        $user->forceDelete();
    });

    //-------------------------- update short link

    it('Update short link', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $url =  Url::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->putJson('/api/urls/' . $url->id, [
            'original_url' => 'https://playcode.io',
            'title' => 'new title',
            'code' => Str::random(6)
        ]);

        $response->assertStatus(200);

        $user->forceDelete();
    });

    //-------------------------- Search In Urls


    it('Search In Urls', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/search', ['query' => 'test']);

        $response->assertStatus(200);

        $user->forceDelete();
    });

    //----------------------------- move short link to trash


    it('Move short link to trash', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $url = Url::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson('/api/urls/' . $url->id);

        $response->assertStatus(200);

        $user->forceDelete();
    });

    //----------------------------- restore short link from trash


    it('Restore short link from trash', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $url = Url::factory()->create(['user_id' => $user->id]);

        $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson('/api/urls/' . $url->id);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->patchJson('/api/restore_url/' . $url->id);

        $response->assertStatus(200);

        $user->forceDelete();
    });

    //----------------------------- remove short link - force delete

    it('Remove short link - force delete', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $url = Url::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->deleteJson('/api/force_delete_url/' . $url->id);

        $response->assertStatus(200);

        $user->forceDelete();
    });

    //---------------------------- get all short links trashed

    it('Get all short links trashed', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/trash');

        $response->assertStatus(200);

        $user->forceDelete();
    });

    // --------------------------- show short link details

    it('Show short link details', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $url = Url::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/urls/' . $url->id);

        $response->assertStatus(200);

        $user->forceDelete();
    });

    // ---------------------------- show short link details - 404 - Not found

    it('Show short link details 404 - Not found', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/urls/1500');

        $response->assertStatus(404);

        $user->forceDelete();
    });

    // -------------------------- Show short link details - 403 - Not owner

    it('Show short link details 403 - Not owner', function () {

        [$user, $token] = Helper::createAuthenticatedUser();

        $userTwo = User::factory()->create();

        $url = Url::factory()->create([
            'user_id' => $userTwo->id
        ]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->getJson('/api/urls/' . $url->id);

        $response->assertStatus(403);

        $user->forceDelete();

        $userTwo->forceDelete();
    });
})->group('url');
