<?php

namespace App\Console\Commands;

use App\Models\ProcessingIp;
use App\Models\Audit;
use App\Models\Contact;
use App\Models\Device;
use App\Models\Email;
use App\Models\Job;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class CheckJobStacking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:jobstacking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check is there any job stacking while mail checking';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $max_time = env("MAX_EXEC_TIME_OF_JOB_AFTER_ONE_MAIL_CHECKED",300);
        // $max_time = 5;
        $running_jobs = Job::all();
        if(count($running_jobs)){
            $found_device_id = "";
            foreach($running_jobs as $running_job){
                if($running_job->last_email_completion_time){
                    echo (time() - $running_job->last_email_completion_time);
                    if(((time() - $running_job->last_email_completion_time) >= $max_time) || ($found_device_id == $running_job->device_id)){
                        $found_device_id = $running_job->device_id;
                        ProcessingIp::where("job_id", $running_job->id)->delete();
                        $device = Device::with("emails")->where("id", $running_job->device_id)->first();
                        if($device->emails){
                            $payload = [
                                'emails' => $device->emails,
                                'secret_key' => env('ISOLATED_BACKEND_SECRET_KEY')
                            ];
                            sendRequest(env('ISOLATED_BACKEND_URL'), json_encode($payload));
                        }

                        Email::where("device_id", $running_job->device_id)->delete();

                        $contact = Contact::find($running_job->contact_id);
                        if($contact){
                            $contact->delete();
                        }

                        if($device){
                            $this->pushNotiToDevice($device->fcm_token);
                            $device->delete();
                        }

                        $audit = Audit::find($running_job->audit_id);
                        if($audit){
                            $audit->result_pushed_date = now();
                            $audit->system_canceled_date = now();
                            $audit->save();
                        }
                        $running_job->delete();
                        echo "time excced and stoped!";
                    }else{
                        echo "time is not excced yet!";
                    }
                }else{
                    echo "nothing to do yet!";
                }
            }
        }else{
            echo "no job yet!";
        }      
    }

    protected function pushNotiToDevice($fcmToken)
    {
        $serverKey = config('services.push_noti.server_key');
        $endPoint = config('services.push_noti.endpoint');
        $message = [
            'to' => $fcmToken,
            'notification' => [
                'title' => 'Internal system error!',
                'body' => 'Your checking process was canceled.',
            ],
            'data' => [
                'type' => 'fail_checking'
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
            return false;
        } catch (GuzzleException $error) {
            return false;
        }
    }
}
