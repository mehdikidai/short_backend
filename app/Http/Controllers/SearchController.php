<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {


        $query = $request->input('query');
        $userId = $request->user()->id;

        $results = Url::where('user_id', $userId)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('code', 'like', '%' . $query . '%')
                    ->orWhere('original_url', 'like', '%' . $query . '%');
            })
            ->latest()
            ->limit(12)
            ->get(['id', 'title', 'original_url']);


        return response()->json($results);
    }
}
