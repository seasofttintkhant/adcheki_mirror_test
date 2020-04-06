<?php

namespace App\Http\Controllers\Api\V1;

use App\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Models\EmailResult;

class EmailResultController extends ApiBaseController
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
        $emailResult = EmailResult::where('device_id', $device_id)
            ->latest()
            ->first();
        if ($emailResult) {
            $emailResults = [];
            foreach ($emailResult->result as $key => $value) {
                $emailResults[] = [
                    'email' => $key,
                    'status' => $value['exist']
                ];
            }
            return $this->jsonResponse(
                200,
                1,
                [],
                [],
                $emailResults
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
