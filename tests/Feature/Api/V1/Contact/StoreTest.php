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
                ['email' => 'mgmg@gmail.com', 'name' => 'Mg Mg', 'phone' => '090000001'],
                ['email' => 'kyawkyaw@gmail.com', 'name' => 'Kyaw Kyaw', 'phone' => '090000002']
            ]
        ];

        // Act
        $response = $this->postJson(route('contacts.store'), $contacts);

        // Assert
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                ['email' => 'mgmg@gmail', 'status' => 'true'],
                ['email' => 'kyawkyaw@gmail', 'status' => 'false']
            ]
        ]);
    }
}
