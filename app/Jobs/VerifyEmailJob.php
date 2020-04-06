<?php

namespace App\Jobs;

use App\Models\EmailResult;
use App\Services\AdvanceEmailValidator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $device_id;


    protected $emails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($device_id, array $emails)
    {
        $this->device_id = $device_id;
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email_validator = new AdvanceEmailValidator();
        $email_validator->setStreamTimeoutWait(20);
        $email_validator->setEmailFrom('seasoft.tint.khant@gmail.com');

        $result = $email_validator->checkEmails($this->emails);

        EmailResult::create([
            'device_id' => $this->device_id,
            'result' => $result
        ]);
    }
}
