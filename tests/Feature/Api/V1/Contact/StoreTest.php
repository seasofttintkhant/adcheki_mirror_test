<?php

namespace Tests\Feature\Api\V1\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function aDeviceCanStoreTheContacts()
    {
        // Arrange
        $contacts = [
            'device_id' => 'abcdef123456',
            'contacts' => [
                [
                    'emails' => ['mgmg@gmail.com', 'mgmg2@gmail.com'],
                    'name' => 'Mg Mg',
                    'phones' => ['09000001', '090000003'],
                ],
                [
                    'emails' => ['kyawkyaw@gmail.com', 'kyawkyaw2@gmail.com'],
                    'name' => 'Kyaw Kyaw',
                    'phones' => ['09000002', '090000004'],
                ]
            ]
        ];
        // Act
        $response = $this->postJson(route('contacts.store'), $contacts);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('devices', [
            'id' => 1,
            'device_id' => $contacts['device_id']
        ]);
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'device_id' => 1,
            'data' => json_encode($contacts['contacts'][0])
        ]);
        $this->assertDatabaseHas('contacts', [
            'id' => 2,
            'device_id' => 1,
            'data' => json_encode($contacts['contacts'][1])
        ]);
        $this->assertDatabaseHas('emails', [
            'id' => 1,
            'contact_id' => 1,
            'email' => $contacts['contacts'][0]['emails'][0],
            'status' => 0
        ]);
        $this->assertDatabaseHas('emails', [
            'id' => 2,
            'contact_id' => 1,
            'email' => $contacts['contacts'][0]['emails'][1],
            'status' => 0
        ]);
        $this->assertDatabaseHas('emails', [
            'id' => 3,
            'contact_id' => 2,
            'email' => $contacts['contacts'][1]['emails'][0],
            'status' => 0
        ]);
        $this->assertDatabaseHas('emails', [
            'id' => 4,
            'contact_id' => 2,
            'email' => $contacts['contacts'][1]['emails'][1],
            'status' => 0
        ]);
        $response->assertExactJson([
            'status' => 1,
            'message' => [],
            'headers' => [],
            'data' => []
        ]);
    }
}
