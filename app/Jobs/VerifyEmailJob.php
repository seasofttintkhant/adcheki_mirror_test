<?php

namespace App\Jobs;

use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Services\AdvanceEmailValidator;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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

        $emailResults = $email_validator->checkEmails($this->emails);

        $device = Device::where('device_id', $this->device_id)->first();
        $device->is_checked = true;
        $device->save();
        foreach ($device->emails as $email) {
            $email->update([
                'is_valid' => $emailResults[$email->email]['valid'],
                'status' => $emailResults[$email->email]['status']
            ]);
        }
    }
}
