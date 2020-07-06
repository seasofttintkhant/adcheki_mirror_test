<?php

namespace App\Jobs;

use App\Models\Audit;
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

    protected $audit_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($device_id, array $emails, $audit_id)
    {
        $this->device_id = $device_id;
        $this->emails = $emails;
        $this->audit_id = $audit_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checkedEmails = [];

        foreach (array_chunk($this->emails, 10) as $emails) {
            if (!Device::where('id', $this->device_id)->exists()) {
                break;
            }
            $checkedEmails = array_merge($checkedEmails, $this->checkEmails($emails));
        }

        $device = Device::latest()->with('emails')->findOrFail($this->device_id);
        if ($device) {
            foreach ($device->emails as $email) {
                if (isset($checkedEmails[$email->email])) {
                    $email->update([
                        'is_valid' => $checkedEmails[$email->email]['is_valid'],
                        'status' => $checkedEmails[$email->email]['status']
                    ]);
                }
            }

            $this->pushNotiToDevice($device->fcm_token);

            $audit = Audit::findOrFail($this->audit_id);
            $audit->update(['result_pushed_date' => now()]);

            $device->is_checked = true;
            $device->save();
        }
    }

    protected function pushNotiToDevice($fcmToken)
    {
        $serverKey = config('services.push_noti.server_key');
        $endPoint = config('services.push_noti.endpoint');
        $message = [
            'to' => $fcmToken,
            'notification' => [
                'title' => 'メールアドレス存在確認完了',
                'body' => 'メールアドレス存在確認が完了しました。アプリを開いて結果を取得してください。',
            ],
            'data' => [
                'type' => 'completed_checking'
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
            Log::error('Push Noti Errors:' . $response->results[0]->error);
            return false;
        } catch (GuzzleException $error) {
            Log::error('Guzzle Errors: ' . $error);
            return false;
        }
    }

    public function checkEmails($emails)
    {
        $header = [
            'Accept: application/json',
            'Content-Type: application/json'
        ];
        $mail_checking_servers = env('MAIL_CHECKING_SERVERS', '');
        $mail_checking_servers = explode(',', $mail_checking_servers);
        $mail_checking_server = $mail_checking_servers[array_rand($mail_checking_servers)];
        $mail_checking_server = 'https://check01.adcheki.jp';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $mail_checking_server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'emails' => $emails,
            'secret_key' => env('MAIL_CHECKING_SERVER_SECRET_KEY', '')
        ]));
        $result = curl_exec($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        return $result;
    }
}
