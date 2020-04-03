<?php

namespace App\Http\Controllers\Api\V1;

use App\Device;
use App\Jobs\VerifyEmailJob;
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
            $storedContact = $storedDevice->contacts()->create([
                'data' => json_encode($contact)
            ]);
            foreach ($contact['emails'] as $email) {
                $storedContact->emails()->create([
                    'email' => $email
                ]);
                array_push($emails, $email);
            }
        }

        VerifyEmailJob::dispatch($request->device_id, $emails);

        return $this->jsonResponse(
            201,
            1,
            [],
            [],
            []
        );
    }
}
