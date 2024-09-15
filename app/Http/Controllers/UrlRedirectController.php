<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;

class UrlRedirectController extends Controller
{
    public function redirect($code)
    {
        
        $url = Url::where('code', $code)->first();

        if ($url) {
            return redirect()->to($url->original_url);
        }

        abort(404);
    }
}
