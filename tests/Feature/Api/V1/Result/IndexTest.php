<?php

namespace Tests\Feature\Api\V1\Result;

use App\Device;
use Tests\TestCase;
use App\Models\Email;
use App\Models\Contact;
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

        $contact = factory(Contact::class)->create([
            'device_id' => $device->id
        ]);

        $emails = factory(Email::class, 2)->create([
            'contact_id' => $contact->id
        ]);

        $arrayEmails = [];
        foreach ($emails as $email) {
            $arrayEmails[] = $email->only('email', 'status');
        }

        // Act
        $response = $this->getJson(route('results.show', $device->device_id));

        // Assert
        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 1,
            'message' => [],
            'headers' => [],
            'data' => $arrayEmails
        ]);
    }
}
