<?php

namespace App\Jobs;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;

class SocketEmit implements ShouldQueue
{
    use Queueable;


    protected $id;

    protected $event;

    /**
     * Create a new job instance.
     */
    public function __construct($event, $id)
    {
        $this->id = $id;

        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        try {

            $url = config("services.socket.url") . '/event';

            $key = config("services.socket.key");

            $response = Http::withHeaders(['socket-key' => $key])->post($url, [

                'event' => $this->event,
                'id' => $this->id

            ]);

            if ($response->successful()) {

                Log::info('socket io :' . $response);
            } else {

                Log::error('socket io error :' . $response);
            }
        } catch (Exception $e) {

            Log::error('socket error :' . $e->getMessage());
        }
    }
}
