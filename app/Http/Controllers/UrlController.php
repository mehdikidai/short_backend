<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreUrlRequest;
use App\Http\Requests\UpdateUrlRequest;

class UrlController extends Controller
{


    public $sortBy = ['desc', 'asc'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user =  auth('sanctum')->user();

        $sortOrder = $request->query('sort_order', 'desc');


        if (!in_array($sortOrder, $this->sortBy)) {

            return abort(404);

        }
        

        if (!$user) {

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $urls = Url::where('user_id', $user->id)->orderBy('created_at', $sortOrder)->paginate(6);


        $urls->getCollection()->transform(function ($url) use ($request) {
            $url->url_server = $request->root();
            $url->domain = preg_replace('/^https?:\/\//', '', $request->root());
            return $url;
        });


        return response()->json($urls);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'original_url' => 'required|url',
            'title' => 'required|regex:/(^[A-Za-z][\w\s]{1,20}[A-Za-z]$)/'
        ]);


        do {
            $data['code'] = Str::random(6);
        } while (Url::where('code', $data['code'])->exists());


        $data['user_id'] = $request->user()->id;

        Url::create($data);

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $url = Url::findOrFail($id);

        Gate::authorize('view-url', $url);

        return response()->json($url);
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'original_url' => 'required|url',
            'title' => 'required|regex:/(^[A-Za-z][\w\s]{1,20}[A-Za-z]$)/',
            'code' => 'required|regex:/(^[A-Za-z0-9]{3,8}$)/|unique:urls,code,' . $id
        ]);

        $url = Url::findOrFail($id);

        Gate::authorize('update-url', $url);

        $url->original_url = $request->input('original_url');
        $url->title = $request->input('title');
        $url->code = $request->input('code');

        $data = $url->save();

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $url = Url::findOrFail($id);

        Gate::authorize('delet-url', $url);

        $res = $url->delete();

        Cache::forget($url->user_id . '_number_of_visits');

        return response()->json(['message' => $res]);
    }


}
