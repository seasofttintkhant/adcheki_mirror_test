<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Job;
use App\Models\Setting;
use Illuminate\Http\Request;

class EVSController extends Controller
{
    //
    public function getIpDomains(Request $request){
        // $a = 1;
        // while ($a <= 250) {
        //     Domain::insert([
        //         "name" => $a.".com",
        //         "default_ip" => $a.".".$a.".".$a.".".$a,
        //         "dns_ip" => $a.".".$a.".".$a.".".$a,
        //         "is_match" => 1
        //     ]);
        //     $a++;
        // }
        // return "xx";
        if($request->get("secret_key", "no_key") != env("ISOLATED_BACKEND_SECRET_KEY")){
            return response()->json([]);
        }

        $key = $request->get("key", "");
        $local_ip_domains = $request->get("local_ip_domains", "");
        $email_count = $request->get("email_count", 0);
        $ips_domains = [];

        // $key = "srv1_first_ip_id_to_use";
        // $local_ip_domains = "1.1.1.1,2.2.2.2,3.3.3.3";
        // $email_count = 2;

        if($key && $local_ip_domains && $email_count){
            $setting = Setting::where("key", $key)->first();
            if($setting){
                $starting_ip = $setting->value;
                $local_ip_domains = explode(",", $local_ip_domains);
                
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

    public function updateJob(Request $request){
        $email = $request->get("email", "");
        $job_id = $request->get("job_id", "");
        if($email && $job_id){
            Job::where("id",$job_id)->update([
                "last_email" => $email
            ]);
        }
        return response()->json([]);
    }
}
