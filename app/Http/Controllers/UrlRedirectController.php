<?php

namespace App\Http\Controllers;

use App\Jobs\GetInfoIp;
use App\Models\Click;
use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class UrlRedirectController extends Controller
{
    public function redirect(Request $request, $code)
    {

        $url = Url::where('code', $code)->first();

        $agent = new Agent();

        $browser = $agent->browser();

        $device = null;

        if ($agent->isTablet()) {
            $device = 'tablet';
        } elseif ($agent->isMobile()) {
            $device = 'mobile';
        } else {
            $device = 'desktop';
        }

        //$version = $agent->version($browser);


        if ($url) {

            $new_click = Click::create([
                'url_id' => $url->id,
                'ip_address' => $request->ip(),
                'browser' => $browser,
                'device' => $device
            ]);

            GetInfoIp::dispatch($new_click);

            Cache::forget($url->user_id . '_number_of_visits');


            return redirect()->to($url->original_url);
        }

        abort(404);
    }
}
