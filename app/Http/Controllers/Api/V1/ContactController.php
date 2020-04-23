<?php

namespace App\Http\Controllers\Api\V1;

use App\Device;
use App\Http\Resources\EmailResultResource;
use App\Jobs\VerifyEmailJob;
use App\Models\EmailResult;
use Illuminate\Http\Request;

class ContactController extends ApiBaseController
{
    public function store(Request $request)
    {
        $storedDevice = Device::create([
            'device_id' => $request->device_id
        ]);
        $emails = [];
        foreach ($request->contacts as $contact) {
            $storedDevice->contacts()->create([
                'data' => json_encode($contact)
            ]);
            foreach ($contact['emails'] as $email) {
                array_push($emails, $email);
            }
        }

        // VerifyEmailJob::dispatch($request->device_id, $emails);

        return response()->json([
            "status" => "success"
        ]);
    }

    public function check(Request $request)
    {
        $emailResult = EmailResult::where('device_id', $request->device_id)->latest()->first();

        return new EmailResultResource($emailResult);
    }
}
