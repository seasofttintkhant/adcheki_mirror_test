<?php

namespace App\Http\Controllers\Api\V1;

use App\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\ApiBaseController;

class ContactController extends ApiBaseController
{
    public function store(Request $request)
    {
        $storedDevice = Device::create([
            'device_id' => $request->device_id
        ]);

        $storedContacts = $storedDevice->contacts()->createMany($request->contacts);

        $emails = $storedContacts->pluck('email')->all();

        $this->validateEmails($emails);

        return $this->jsonResponse(
            201,
            1,
            [],
            [],
            [
                ['email' => 'mgmg@gmail', 'status' => 'true'],
                ['email' => 'kyawkyaw@gmail', 'status' => 'false']
            ]
        );
    }

    public function validateEmails(array $emails = [])
    { }
}
