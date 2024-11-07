<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Models\Click;
use App\Jobs\VirusTotal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class UrlController extends Controller
{




    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $sort = 'desc')
    {

        $user =  auth('sanctum')->user();

        if (!$user) {

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $urls = Url::where('user_id', $user->id)->orderBy('created_at', $sort)->paginate(6);

        $urls->getCollection()->transform(function ($url) use ($request) {
            $url->url_server = $request->root();
            $url->domain = preg_replace('/^https?:\/\//', '', $request->root());
            return $url;
        });


        return response()->json($urls);
    }

    //---------------------------------------------------


    public function trash(Request $request)
    {

        $user =  $request->user();


        if (!$user) {

            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $urls = Url::where('user_id', $user->id)->onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);


        return response()->json($urls);
    }

    //---------------------------------------------------

    public function forceDeleteUrl($id)
    {

        $url = Url::withTrashed()->findOrFail($id);

        Gate::authorize('delet-url', $url);

        $res = $url->forceDelete();

        return response()->json(['success' => $res]);
    }
    //---------------------------------------------------


    public function restoreUrl($id)
    {

        $url = Url::withTrashed()->findOrFail($id);

        Gate::authorize('update-url', $url);

        $res = $url->restore();

        return response()->json(['success' => $res]);
    }


    //---------------------------------------------------



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([

            'title' => 'required|regex:/(^[A-Za-z][\w\s]{1,20}[A-Za-z]$)/',

            'original_url' => ['required', 'url', Rule::unique('urls')->where(function ($query) use ($request) {
                return $query->where('user_id', $request->user()->id);
            })]

        ], ['original_url.unique' => 'You already have a URL.']);


        do {
            $data['code'] = Str::random(6);
        } while (Url::where('code', $data['code'])->exists());


        $data['user_id'] = $request->user()->id;

        Url::create($data);

        VirusTotal::dispatch();

        $this->forgetCache([$request->user()->id . '_number_of_visits']);

        return response()->json($data, 201);
    }

    //---------------------------------------------------

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {

        $url = Url::with(['clicks' => function ($query) {

            $query->select('id', 'url_id', 'created_at', 'browser', 'device');
        }])->withCount('clicks')->findOrFail($id);


        Gate::authorize('view-url', $url);


        $topDevice = Click::select('device', DB::raw('count(*) as total'))
            ->where('url_id', $url->id)
            ->groupBy('device')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();

        $topBrowsers = Click::select('browser', DB::raw('count(*) as total'))
            ->where('url_id', $url->id)
            ->groupBy('browser')
            ->orderBy('total', 'desc')
            ->limit(4)
            ->get();


        $url->url_server = $request->root();
        $url->domain = preg_replace('/^https?:\/\//', '', $request->root());
        $url->top_devices = $topDevice;
        $url->top_browsers = $topBrowsers;


        return response()->json($url);
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

        $this->forgetCache([$url->user_id . '_number_of_visits']);

        return response()->json(['message' => $res]);
    }

    // --------------------------------------

    private function forgetCache($names)
    {

        foreach ($names as $name) {
            Cache::forget($name);
        }
    }

    // --------------------------------------

    public function visualUrl($id)
    {


        $url = Url::findOrFail($id);

        Gate::authorize('update-url', $url);

        $url->visible = !$url->visible;

        $url->save();

        return response()->json([
            'id' => $url->id,
            'visible' => $url->visible,
            'message' => 'visibility status updated successfully'
        ]);
    }
}
