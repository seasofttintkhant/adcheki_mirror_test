<?php

namespace Tests\Feature\Api\V1\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function aDeviceCanStoreTheContactsAndCheckEmails()
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
                    'phones' => ['09000002', '090000004']
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

        ]);
        // $response->assertExactJson([
        //     'status' => 1,
        //     'message' => [],
        //     'headers' => [],
        //     'data' => [
        //         ['email' => 'mgmg@gmail', 'status' => 'true'],
        //         ['email' => 'kyawkyaw@gmail', 'status' => 'false']
        //     ]
        // ]);
    }
}
