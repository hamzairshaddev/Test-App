<?php

namespace App\Jobs;

use Mail;
use App\Mail\QuotesEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = json_decode($data, true);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->data['requestData']['email'])
                        ->send(new QuotesEmail($this->data));
    }
}
