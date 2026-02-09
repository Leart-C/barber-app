<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVerificationCode implements ShouldQueue
{
     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $phone,
        public string $code
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        logger()->info('Mock SMS verification code',[
            'phone' => $this->phone,
            'code' => $this->code,
        ]);
    }
}
