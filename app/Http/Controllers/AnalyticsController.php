<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Url;
use App\Enums\FilterType;
use App\Models\Click;
use Illuminate\Http\Request;
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


        return response()->json([
            'total_urls' => $total_urls,
            'urls' => $total_visits,
            'urls_trash' => $onlySoftDeleted,
            'number_of_visits' => $number_of_visits
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}