<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\Click;
use App\Jobs\GetInfoIp;
use App\Jobs\SocketEmit;
use App\Models\User;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UrlRedirectController extends Controller
{

    public function __invoke(Request $request, $code)
    {

        $url = Url::where('code', $code)->first();

        $user = User::findOrFail($url->user_id);

        $agent = new Agent();

        $browser = $agent->browser();

        $device = match (true) {
            $agent->isTablet() => 'tablet',
            $agent->isMobile() => 'mobile',
            default => 'desktop'
        };

        $new_click = Click::create([
            'url_id' => $url->id,
            'ip_address' => $request->ip(),
            'browser' => $browser,
            'device' => $device
        ]);

        GetInfoIp::dispatch($new_click);

        Cache::forget((string) $url->id . '_number_of_visits');

        SocketEmit::dispatch('newVisit', $user->socket_room);

        return redirect()->to($url->original_url);

    }
}
