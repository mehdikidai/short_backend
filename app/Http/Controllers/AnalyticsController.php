<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Url;
use App\Models\User;
use App\Models\Click;
use App\Enums\FilterType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,  ?FilterType $filter = FilterType::All)
    {


        if (!in_array($filter, FilterType::cases())) {
            return response()->json(['error' => 'Invalid filter specified.'], 404);
        }

        $user = $request->user();

        $startDate = $this->filter_by_date($filter);

        $endDate = Carbon::now()->endOfDay();


        $total_urls = $user->urls()->whereBetween('created_at', [$startDate, $endDate])->count();


        $onlySoftDeleted = Url::where('user_id', $user->id)->onlyTrashed()->whereBetween('deleted_at', [$startDate, $endDate])->count();


        $ids = Url::where('user_id', $user->id)->pluck('id')
            ->toArray();

        $total_visits = Url::where('user_id', $user->id)
            ->withCount(['clicks' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->latest()
            ->get();

        $number_of_visits = Cache::remember($user->id . '_number_of_visits', 60 * 60, function () use ($ids) {

            return $this->getNumberOfVisits($ids);
        });  //


        $most_countries = $this->most_countries($user->id, $startDate, $endDate);

        return response()->json([
            'total_urls' => $total_urls,
            'urls' => $total_visits,
            'urls_trash' => $onlySoftDeleted,
            'number_of_visits' => $number_of_visits,
            'most_countries' => $most_countries,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /***
     *  show all locations map
     * 
     *
     */

    public function showLocations(Request $request, ?FilterType $filter = FilterType::All)
    {

        $user = $request->user();

        if (!in_array($filter, FilterType::cases())) {
            return response()->json(['error' => 'Invalid filter specified.'], 404);
        }

        $startDate = $this->filter_by_date($filter);

        $endDate = Carbon::now()->endOfDay();

        //$locations = Url::where('user_id', $user->id)->with('clicks:id,url_id,lat,lon')->get();

        $clicks = Click::whereBetween('created_at', [[$startDate, $endDate]])
            ->whereHas('url', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->select('id', 'url_id', 'lat', 'lon', 'city')
            ->get();


        return response()->json($clicks);
    }



    private function filter_by_date(FilterType $filter): Carbon
    {
        return match ($filter) {
            FilterType::LastDay => Carbon::now()->subDay(),
            FilterType::LastWeek => Carbon::now()->subWeek(),
            FilterType::LastMonth => Carbon::now()->subMonth(),
            FilterType::LastYear => Carbon::now()->subYear(),
            FilterType::All => Carbon::now()->startOfYear(),
        };
    }

    private function getNumberOfVisits($ids)
    {
        $arr = [];
        $i = 0;

        while ($i <= 4) {

            $date = Carbon::now()->subDays($i)->toDateString();

            $count = Click::whereIn('url_id', $ids)
                ->whereDate('created_at', $date)
                ->count();

            $arr[$date] = $count;

            $i++;
        }

        return $arr;
    }

    private function most_countries($id, $startDate, $endDate)
    {

        $topCountries = Click::join('urls', 'clicks.url_id', '=', 'urls.id')

            ->where('urls.user_id', $id)
            ->whereBetween('clicks.created_at', [$startDate, $endDate])
            ->select('clicks.country', 'clicks.country_code as code', DB::raw('COUNT(*) as visits'))
            ->groupBy('clicks.country', 'clicks.country_code')
            ->orderByDesc('visits')
            ->get();

        return $topCountries;
    }
}
