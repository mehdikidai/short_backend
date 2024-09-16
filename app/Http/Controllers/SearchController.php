<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;

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


        return response()->json($results);
    }
}
