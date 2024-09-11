<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Http\Requests\StoreUrlRequest;
use App\Http\Requests\UpdateUrlRequest;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user =  auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $urls = Url::where('user_id', $user->id)->paginate(5);

        return response()->json($urls);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUrlRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Url $url)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Url $url)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUrlRequest $request, Url $url)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Url $url)
    {
        //
    }
}
