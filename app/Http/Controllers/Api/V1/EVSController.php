<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Device;
use App\Models\Domain;
use App\Models\Email;
use App\Models\Job;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EVSController extends Controller
{
    //
    public function getIpDomains(Request $request){
        if($request->get("secret_key", "no_key") != env("EVS_SECRET_KEY")){
            return response()->json([]);
        }

        $key = $request->get("key", "");
        $local_ip_domains = $request->get("local_ip_domains", []);
        $email_count = $request->get("email_count", 0);
        $ips_domains = [];

        if($key && $local_ip_domains && $email_count){
            $setting = Setting::where("key", $key)->first();
            if($setting){
                $starting_ip = $setting->value;
                $system_ip_domains = Domain::whereIn("default_ip", $local_ip_domains)->where("is_match", 1)->orderBy("id", "ASC")->get();
                $server_ip_domains = [];
                foreach($system_ip_domains as $system_ip_domain){
                    $server_ip_domains[$system_ip_domain->id] = [
                        $system_ip_domain->default_ip => $system_ip_domain->name
                    ];
                }

                $sv_ips = $system_ip_domains->pluck("id")->toArray();

                $starting_ip = $starting_ip ? $starting_ip : $sv_ips[0];

                $index_of_starting_ip = array_search($starting_ip, $sv_ips);
                if(count($sv_ips) >= $email_count){
                    $index_of_next_starting_ip = $index_of_starting_ip + $email_count;
                    if(isset($sv_ips[$index_of_next_starting_ip])){
                        $next_starting_ip = $sv_ips[$index_of_next_starting_ip];
                    }else{
                        $index_of_next_starting_ip = $index_of_next_starting_ip - count($sv_ips);
                        $next_starting_ip = $sv_ips[$index_of_next_starting_ip];
                    }
                }else{
                    $index_of_next_starting_ip = $email_count % count($sv_ips);
                    $next_starting_ip = $sv_ips[$index_of_next_starting_ip];
                }

                // return $next_starting_ip;

                Setting::where("key", $key)->update([
                    "value" => $next_starting_ip
                ]);

                $ips_to_be_used = [];
                if($starting_ip < $next_starting_ip){
                    $ips_to_be_used = array_slice($sv_ips, $index_of_starting_ip, $email_count);
                }else{
                    $ips_to_be_used_1 = array_slice($sv_ips, $index_of_starting_ip, $email_count);
                    $ips_to_be_used_2 = array_slice($sv_ips, 0, $index_of_next_starting_ip - count($sv_ips));
                    $ips_to_be_used = array_merge($ips_to_be_used_1, $ips_to_be_used_2);
                }

                foreach($ips_to_be_used as $ip_to_be_used){
                    $ips_domains[array_keys($server_ip_domains[$ip_to_be_used])[0]] = array_values($server_ip_domains[$ip_to_be_used])[0];
                }

                return response()->json($ips_domains);
            }
        }

        return response()->json($ips_domains);
    }

    public function updateJobAndEmailResult(Request $request){
        if($request->get("secret_key", "no_key") != env("EVS_SECRET_KEY")){
            return response()->json([]);
        }
        
        $email = $request->get("email", "");
        $is_valid = $request->get("is_valid", 1);
        $is_exist = $request->get("status", 0);
        $job_id = $request->get("job_id", "");
        $check_server = $request->get("check_server", "");
        if($email && $job_id){
            // \DB::table("temp_email_logs")->insert([
            //     "email" => $email,
            //     "job_id" => $job_id
            // ]);
            $job = Job::where("id",$job_id)->first();
            if($job){
                \Log::info("HIT FROM EVS: JOB_ID ".$job_id);
                \Log::info("HIT FROM EVS: DEVICE_ID ".$job->device_id);
                \Log::info("HIT FROM EVS: EMAIL ".$email);
                $job->update([
                    "last_email" => $email,
                    "last_email_completion_time" => time()
                ]);
                Email::where("device_id", $job->device_id)->where("email", $email)->update([
                    'is_valid' => $is_valid,
                    'status' => $is_exist,
                    'ok' => calcResult($is_valid, $is_exist)[0],
                    'ng' => calcResult($is_valid, $is_exist)[1],
                    'unknown' => calcResult($is_valid, $is_exist)[2],
                    'is_checked' => 1
                ]);

                $checked_emails = Email::where("device_id", $job->device_id)->where("is_checked", 1)->count();
                $audit = Audit::where("id", $job->audit_id)->update([
                    "checked_emails_count" => $checked_emails
                ]);

                $unchecked_emails = Email::where("device_id", $job->device_id)->where("is_checked", 0)->count();

                if (!$unchecked_emails) {
                    Job::where("device_id",$job->device_id)->delete();
                    $device = Device::where("id", $job->device_id)->first();
                    if($device){
                        $audit = Audit::where("id", $job->audit_id)->first();
                        \Log::info("SEND PUSH");
                        \Log::info("END");
                        pushNotiToDevice($device->fcm_token);
                        $audit->update(['result_pushed_date' => now()]);
                        $device->is_checked = true;
                        $device->save();
                        \Log::channel("custom_log_1")->info("END TIME FOR DEVICE_ID: ".$device->id. " -> ".time());
                    }
                }else{
                    \Log::info("EMAILS STILL LEFT");
                }
                return $email;
            }
            return "EMAIL_NOT_PROCESSED: EMAILEXISTS JOBNOTFOUND: JOBID ".$job_id." : ".$email;
        }
        return "EMAIL_NOT_PROCESSED: NOEMAIL NOJOB_ID: ".$email;
        return response()->json([]);
    }
}
