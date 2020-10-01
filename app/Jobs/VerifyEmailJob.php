<?php

namespace App\Jobs;

use App\Models\Audit;
use App\Models\Contact;
use App\Models\Device;
use App\Models\Email;
use App\Models\Job;
use App\Models\TempEmail;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
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

    protected $contact_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($device_id, array $emails, $audit_id, $contact_id)
    {
        $this->device_id = $device_id;
        $this->emails = $emails;
        $this->audit_id = $audit_id;
        $this->contact_id = $contact_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $job = Job::find($this->job->getJobId());
        $job->audit_id = $this->audit_id;
        $job->device_id = $this->device_id;
        $job->contact_id = $this->contact_id;
        $job->save();
        foreach (array_chunk($this->emails, 25) as $emails) {
            
            $device = Device::latest()->with('emails')->find($this->device_id);
            if (!$device) {
                break;
            }

            $uniqueEmails = [];
            foreach ($emails as $email) {
                if (!in_array(Str::lower($email), $uniqueEmails)) {
                    $uniqueEmails[] = Str::lower($email);
                }
                $device->emails()->create([
                    'email' => $email,
                    'is_valid' => 1,
                    'status' => 0,
                    'ok' => calcResult(1, 0)[0],
                    'ng' => calcResult(1, 0)[1],
                    'unknown' => calcResult(1, 0)[2],
                    'os' => $device->os
                ]);
            }

            $this->checkEmails($uniqueEmails);       
        }
    }

    protected function pushNotiToDevice($fcmToken)
    {
        $serverKey = config('services.push_noti.server_key');
        $endPoint = config('services.push_noti.endpoint');
        $message = [
            'to' => $fcmToken,
            'notification' => [
                'title' => 'チェックが完了しました！',
                'body' => '結果タブでチェック結果をご確認ください。',
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
        $mail_checking_servers = env('MAIL_CHECKING_SERVERS', '');
        $mail_checking_servers = explode(',', $mail_checking_servers);
        $mail_checking_server = $mail_checking_servers[array_rand($mail_checking_servers)];
        // $mail_checking_server = 'https://check01.adcheki.jp';
        $payload = [
            'emails' => $emails,
            'job_id' => $this->job->getJobId(),
            'secret_key' => env('MAIL_CHECKING_SERVER_SECRET_KEY', '')
        ];
        return sendRequest($mail_checking_server, json_encode($payload));
    }

    public function mockEmailCheck($emails){
        $checkedEmails = [];
        foreach($emails as $key => $email){
            if($key == 1){
                sleep(1);
            }else{
                sleep(1);
            }
            $checkedEmail = [
                "is_valid" => 1,
                "status" => 2
            ];
            $checkedEmails[$email] = $checkedEmail;
            \DB::table("jobs")->where("id",$this->job->getJobId())->update(["last_email_completion_time" => time(), "last_email" => $email]);
        }
        return $checkedEmails;
    }

    public function calcResult($is_valid, $is_exist){
        $ok = 0;
        $ng = 0;
        $unknown = 0;
        if($is_valid == 0 && $is_exist == 1){
            $ng = 1;
        }else if($is_valid == 1 && $is_exist == 2){
            $ok = 1;
        }else if($is_valid == 1 && $is_exist == 1){
            $ng = 1;
        }else if($is_valid == 1 && $is_exist == 0){
            $unknown = 1;
        }
        return [$ok, $ng, $unknown];
    }
}
