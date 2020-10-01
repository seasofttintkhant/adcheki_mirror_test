<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IsolatedController extends Controller
{
    //
    public function syncDueEmails(Request $request){
        if($request->get("secret_key", "no_key") != env("ISOLATED_BACKEND_SECRET_KEY")){
            return response()->json([]);
        }
        $due_time = $request->get("due_time", Carbon::now()->subDay());
        $delete = $request->get("delete", 0);
        $emails = Email::where('updated_at', '<=', $due_time)
            ->orderBy("email","ASC")
            ->orderBy("os","ASC")
            ->paginate(500);
        $emails2 = $emails;
        if($delete){
            Email::whereIn("id", $emails->pluck("id")->toArray())->delete();
        }        
        return response()->json(["data" => $emails2]);
    }

    private function generateFakeResults(){
        $a = 1;
        while ($a <= 1000) {
            $fake_result = $this->fakeResult();
            $data = [
                "email" => "email".$a.".com",
                "is_valid" => $fake_result[0],
                "status" => $fake_result[1],
                "ok" => $fake_result[2],
                "ng" => $fake_result[3],
                "unknown" => $fake_result[4],
                "os" => rand(0,2),
                "is_checked" => 1,
                "created_at" => Carbon::now()->subDays(2),
                "updated_at" => Carbon::now()->subDays(2),
            ];
            Email::create($data);
            $a++;
        }
        return "done";
    }

    private function fakeResult(){
        $data = [
            [0,1,1,0,0],
            [1,2,0,1,0],
            [1,1,1,0,0],
            [1,0,0,0,1],
        ];
        return $data[array_rand($data)];
    }
}
