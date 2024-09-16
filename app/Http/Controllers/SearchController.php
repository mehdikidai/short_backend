<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {


        $query = $request->input('query');
        $userId = $request->user()->id;

        $results = Url::where('user_id', $userId)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('code', 'like', '%' . $query . '%');
            })
            ->latest()
            ->limit(6)
            ->get();

            

        Log::info('hi mehdi'.Str::random(10));


        return response()->json($results);
    }
}
