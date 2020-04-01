<?php

namespace Tests\Unit\Api\V1\Contact;

use App\Device;
use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCanStoreAContact()
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

        $storedDevice = Device::create(['device_id' => $contacts['device_id']]);

        // Act
        foreach ($contacts['contacts'] as $contact) {
            $storedDevice->contacts()->create([
                'data' => json_encode($contact)
            ]);
        }

        $storedContacts = Contact::all();

        // Assert
        $this->assertDatabaseHas('contacts', [
            'id' => 1,
            'device_id' => $storedDevice->id,
            'data' => json_encode($contacts['contacts'][0])
        ]);
        $this->assertDatabaseHas('contacts', [
            'id' => 2,
            'device_id' => $storedDevice->id,
            'data' => json_encode($contacts['contacts'][1])
        ]);

        $this->assertEquals(json_encode($contacts['contacts'][0]), $storedContacts[0]->data);
        $this->assertEquals(json_encode($contacts['contacts'][1]), $storedContacts[1]->data);
    }
}
