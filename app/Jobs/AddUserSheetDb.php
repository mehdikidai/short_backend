<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AddUserSheetDb implements ShouldQueue
{
    use Queueable;

    private $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        $urlSheetDb = config('services.sheetdb.url');
        $tokenSheetDb = config('services.sheetdb.token');

        try {

            $response = Http::withToken($tokenSheetDb)->post($urlSheetDb, [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'date' => Carbon::now()->toDateTimeString(),
            ]);

            if ($response->successful()) {
                Log::info('sheetdb:', $response->json());
            } else {
                Log::error('failed sheetdb:', $response->json());
            }
        } catch (\Exception $e) {
            Log::error('error sheetdb: ' . $e->getMessage());
        }
    }
}
