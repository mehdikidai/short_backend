<?php

namespace App\Http\Middleware;

use App\Models\Url;
use Closure;
use Illuminate\Http\Request;

class VisualUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {


        $code = $request->route('code');


        $url = Url::where('code', $code)->where('visible', true)->first();


        if (!$url) {

            return abort(404);

        }


        return $next($request);

    }
}
