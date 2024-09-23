<?php

namespace App\Http\Controllers;

use App\Models\Click;
use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Support\Facades\Cache;

class UrlRedirectController extends Controller
{
    public function redirect(Request $request, $code)
    {

        $url = Url::where('code', $code)->first();


        if ($url) {

            Click::create([
                'url_id' => $url->id,
                'ip_address' => $request->ip()
            ]);

            Cache::forget($url->user_id . '_number_of_visits');


            return redirect()->to($url->original_url);
        }

        abort(404);
    }
}
