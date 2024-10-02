<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class VirusTotal implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::post('https://www.virustotal.com/vtapi/v2/url/scan', [
            'apikey' => "b0c4f8892443a47c15b80ea3e2d75b2e4074782b46bbe3b6da1310840026d8e6",
            'url' => "https://learnvue.co/articles/vue-emit-guide",
        ]);

        if ($response->successful()) {
            Log::info('Scan successful', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
        } else {
            Log::error('Scan failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }
}
