<?php

namespace App\Jobs;

use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmailResetPassword implements ShouldQueue
{
    use Queueable;

    private $email;
    private $v_code;
    private $name;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $v_code, $name)
    {
        $this->email = $email;
        $this->v_code = $v_code;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Code reset password :' . $this->v_code . ' To Email :' . $this->email);
        Mail::to($this->email)->send(new ResetPassword($this->name, $this->v_code));
    }
}
