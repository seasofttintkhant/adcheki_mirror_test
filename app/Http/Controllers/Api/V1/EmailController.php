<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Email;
use App\Models\Device;
use App\Jobs\VerifyEmailJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmailCollection;

class EmailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contacts = json_decode($request->contacts, true);
        if ($request->device_id === null
        || $request->fcm_token === null
        || $request->os === null
        || $contacts === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request format.'
            ]);
        }

        if (Device::where('device_id', $request->device_id)->exists()) {
            $existingDevice = Device::firstWhere('device_id', $request->device_id);
            $existingDevice->delete();
        }

        $storedDevice = Device::create([
            'device_id' => $request->device_id,
            'fcm_token' => $request->fcm_token,
            'os' => $request->os,
            'is_bulk' => true
        ]);

        $emails = [];
        foreach (json_decode($request->contacts, true) as $contact) {
            $storedDevice->contacts()->create([
                'data' => json_encode($contact)
            ]);
            foreach ($contact['emails'] as $email) {
                array_push($emails, $email);
                $storedDevice->emails()->create([
                    'email' => $email,
                ]);
            }
        }

        VerifyEmailJob::dispatch($storedDevice->id, $emails);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getResults(Request $request)
    {
        $device = Device::where('device_id', $request->device_id)
            ->with('contacts')
            ->with('emails')
            ->where('is_bulk', 1)
            ->latest()
            ->first();

        if ($device) {
            if (!$device->is_checked) {
                return response()->json([
                    'device_id' => $request->device_id,
                    'created_at' => $device->created_at->format('Y-m-d H:i:s'),
                    'result' => []
                ]);
            }

            if ($device->is_checked) {
                $results = Email::with('device')->where('device_id', $device->id)->get();
                return new EmailCollection($results);
            }
        }

        return response()->json([
            'device_id' => $request->device_id,
            'created_at' => now()->format('Y-m-d H:m:i'),
            'result' => []
        ]);
    }

    public function individualCheck(Request $request)
    {
        $emails = explode(',', $request->emails);

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

        $deviceEmails = [];
        foreach ($emails as $email) {
            $deviceEmails[] = [
                'email' => $email,
                'is_valid' => $result[$email]['is_valid'],
                'status' => $result[$email]['status']
            ];
        }

        $device = [
            'device_id' => $request->device_id,
            'fcm_token' => null,
            'os' => $request->os,
            'is_bulk' => false,
            'emails' => $deviceEmails
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('ISOLATED_BACKEND_URL'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'device' => $device,
            'secret_key' => env('ISOLATED_BACKEND_SECRET_KEY')
        ]));
        $result = curl_exec($ch);
        $result = json_decode($result, true);
        curl_close($ch);

        if ($result['status'] === 'success') {
            $formattedEmails = [];
            foreach ($deviceEmails as $email) {
                $formattedEmails[] = [
                    'email' => $email['email'],
                    'valid' => $email['is_valid'] ? true : false,
                    'exist' => $email['status'] == 2
                ];
            }
            return response()->json([
                'device_id' => $request->device_id,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'result' => $formattedEmails
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Cannot send data to the isolated backend.'
        ]);
    }

    public function resultsStatus(Request $request)
    {
        if ($request->device_id === null && ($request->status != 200 || $request->status != 400)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request format.'
            ]);
        }

        if ($request->status == 200) {
            $device = Device::with(['contacts', 'emails'])
                ->latest()
                ->firstWhere('device_id', $request->device_id);
            if ($device === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The device not found.'
                ]);
            }

            $header = [
                'Accept: application/json',
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('ISOLATED_BACKEND_URL'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'device' => $device,
                'secret_key' => env('ISOLATED_BACKEND_SECRET_KEY')
            ]));
            $result = curl_exec($ch);
            $result = json_decode($result, true);
            curl_close($ch);

            if ($result['status'] === 'success') {
                $device->delete();
                return response()->json([
                    'status' => 'success'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Cannot send data to the isolated backend.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'The device is note deleted.'
        ]);
    }

    public function completed(Request $request)
    {
        $device = Device::where('device_id', $request->device_id)
            ->where('is_checked', 1)
            ->first();
        return response()->json([
            'completed' => $device ? true : false,
            'downloaded' => $device ? false : true
        ]);
    }
}
