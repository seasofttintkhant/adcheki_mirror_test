<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    public function updateFcmToken(Request $request)
    {
        $device = Device::where('device_id', $request->device_id)
            ->latest('id')
            ->first();
        $device->update([
            'fcm_token' => $request->fcm_token
        ]);
        return response()->json([
            'status' => 'success'
        ]);
    }
}
