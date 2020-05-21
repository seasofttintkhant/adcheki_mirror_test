<?php

namespace App\Jobs;

use App\Models\Device;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
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
        $header = array(
            'Accept: application/json',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://check01.adcheki.jp");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["emails" => $this->emails]));
        $result = curl_exec($ch);
        $result = json_decode($result,true);
        curl_close($ch);

        $device = Device::with('emails')
            ->where('device_id', $this->device_id)
            ->latest('id')
            ->first();
        foreach ($device->emails as $email) {
            $email->update([
                'is_valid' => $result[$email->email]['is_valid'],
                'status' => $result[$email->email]['status']
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
