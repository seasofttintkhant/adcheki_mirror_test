<?php

namespace App\Http\Controllers\Api\V1;

use App\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\ApiBaseController;

class ResultController extends ApiBaseController
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($device_id)
    {
        $device = Device::where('device_id', $device_id)->first();
        if ($device) {
            $emails = [];
            foreach ($device->emails as $email) {
                $emails[] = $email->only('email', 'status');
            }
            return $this->jsonResponse(
                200,
                1,
                [],
                [],
                $emails
            );
        }
        return $this->jsonResponse(
            404,
            0,
            [],
            ['error' => 'Device not found.'],
            []
        );
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
}
