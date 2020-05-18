<?php

namespace App\Jobs;

use App\Models\Device;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Services\AdvanceEmailValidator;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Exception\GuzzleException;
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

        $device = Device::with('emails')
            ->where('device_id', $this->device_id)
            ->latest('id')
            ->first();
        foreach ($device->emails as $email) {
            $email->update([
                'is_valid' => $emailResults[$email->email]['valid'],
                'status' => $emailResults[$email->email]['status']
            ]);
        }

        $this->pushNotiToDevice($device->fcm_token);

        $device->is_checked = true;
        $device->save();
    }

    protected function pushNotiToDevice($fcmToken)
    {
        $serverKey = config('services.push_noti.server_key');
        $endPoint = config('services.push_noti.endpoint');
        $message = [
            'to' => $fcmToken,
            'notification' => [
                'title' => 'Complete',
                'body' => 'Email checking is complete.'
            ]
        ];
        try {
            $client = new Client([
                'headers' => [
                    'Authorization' => 'key=' . $serverKey,
                    'Accept' => 'application/json'
                ],
            ]);
            $response = $client->post(
                $endPoint,
                [
                    'json' => $message,
                ]
            );
            $response = json_decode($response->getBody()->getContents());
            if ($response->success) {
                return true;
            }
            Log::error('Push noti errors.');
            return false;
        } catch (GuzzleException $error) {
            Log::error('Push noti errors ' . $error);
            return false;
        }
    }
}
