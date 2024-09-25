<?php

namespace App\Jobs;

use App\Models\Click;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetInfoIp implements ShouldQueue
{
    use Queueable;


    public $ip;
    public $id;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->ip = $data->ip;
        $this->id = $data->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        //Log::info("ip :$this->ip and id:$this->id");

        $response = Http::get('http://ip-api.com/json/24.48.0.1');

        //$response = Http::get("http://ip-api.com/json/{$this->ip}");


        if ($response->successful()) {

            $click = Click::find($this->id);

            if ($click) {

                $click->country = $response['country'] ?? null;
                $click->country_code = $response['countryCode'] ?? null;
                $click->lat = $response['lat'] ?? null;
                $click->lon = $response['lon'] ?? null;
                $click->save();
            }
        } else {
            Log::error("Failed to get IP information for: {$this->ip}");
        }
    }
}
