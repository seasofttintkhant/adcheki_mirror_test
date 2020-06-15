<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Device;
use App\Jobs\VerifyEmailJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmailCollection;
use App\Http\Resources\OldEmailCollection;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
        // $storedDevice = Device::updateOrCreate(
        //     ['device_id' => $request->device_id],
        //     ['fcm_token' => $request->fcm_token, 'os' => $request->os, 'is_bulk' => true]
        // );

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getResults(Request $request)
    {
        $devices = Device::where('device_id', $request->device_id)
            ->where('is_bulk', 1)
            ->with('emails')
            ->latest('id')
            ->get();

        if (count($devices) > 0) {
            if ($devices[0] && !$devices[0]->is_checked) {
                if (count($devices) > 1) {
                    $oldDevice = $devices[1];
                    $results = $oldDevice->emails;
                    return new OldEmailCollection($results);
                }
                return response()->json([
                    'device_id' => $request->device_id,
                    'created_at' => $devices[0]->updated_at->format('Y-m-d H:i:s'),
                    'result' => []
                ]);
            }

            if ($devices[0] && $devices[0]->is_checked) {
                $results = $devices[0]->emails;
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
        // $storedDevice = Device::updateOrCreate(
        //     ['device_id' => $request->device_id],
        //     ['os' => $request->os, 'is_bulk' => false]
        // );

        $storedDevice = Device::create([
            'device_id' => $request->device_id,
            'os' => $request->os,
            'is_bulk' => false
        ]);

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

        foreach ($emails as $email) {
            $storedDevice->emails()->create([
                'email' => $email,
                'is_valid' => $result[$email]['is_valid'],
                'status' => $result[$email]['status']
            ]);
        }

        $results = $storedDevice->emails;

        $storedDevice->is_checked = true;
        $storedDevice->save();

        return new EmailCollection($results);
    }

    public function deleteEmails(Request $request)
    {
        $devices = Device::where('device_id', $request->device_id)
            ->with('emails')
            ->get();

        if ($devices) {
            $emails = explode(',', $request->emails);
            foreach ($devices as $device) {
                $device->emails()->whereIn('email', $emails)->delete();
            }

            return response()->json(['status' => 'success']);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'The device not found.'
        ]);
    }
}
