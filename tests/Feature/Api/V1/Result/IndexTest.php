<?php

namespace Tests\Feature\Api\V1\Result;

use App\Device;
use Tests\TestCase;
use App\Models\Contact;
use App\Models\EmailResult;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function aDeviceCanRetrieveTheResult()
    {
        // Arrange
        $device = factory(Device::class)->create();

        factory(Contact::class)->create([
            'device_id' => $device->id
        ]);

        factory(EmailResult::class, 2)->create([
            'device_id' => $device->device_id
        ]);

        $emailResult = EmailResult::where('device_id', $device->device_id)
            ->latest()
            ->first();

        $assertEmails = [];
        foreach ($emailResult->result as $key => $value) {
            $assertEmails[] = [
                'email' => $key,
                'status' => $value['exist']
            ];
        }

        // Act
        $response = $this->getJson(route('email-results.show', $device->device_id));

        // Assert
        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 1,
            'message' => [],
            'headers' => [],
            'data' => $assertEmails
        ]);
    }
}
