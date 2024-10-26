<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\Click;
use App\Jobs\GetInfoIp;
use App\Jobs\SocketEmit;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UrlRedirectController extends Controller
{
    public function redirect(Request $request, $code)
    {

        $url = Url::where('code', $code)->first();

        $agent = new Agent();

        $browser = $agent->browser();

        $device = match(true) {
            $agent->isTablet() => 'tablet',
            $agent->isMobile() => 'mobile',
            default => 'desktop'
        };

        if ($url) {

            $new_click = Click::create([
                'url_id' => $url->id,
                'ip_address' => $request->ip(),
                'browser' => $browser,
                'device' => $device
            ]);

            GetInfoIp::dispatch($new_click);

            Cache::forget($url->user_id . '_number_of_visits');

            SocketEmit::dispatch('newVisit', $url->user_id);

            return redirect()->to($url->original_url);
        }

        abort(404);
    }
}
