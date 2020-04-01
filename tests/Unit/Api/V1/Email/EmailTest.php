<?php

namespace Tests\Unit\Api\V1\Email;

use App\Device;
use Tests\TestCase;
use App\Models\Contact;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCanStoreTheEmails()
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
            $storedContact = $storedDevice->contacts()->create([
                'data' => json_encode($contact)
            ]);
            foreach ($contact['emails'] as $email) {
                $storedContact->emails()->create([
                    'email' => $email
                ]);
            }
        }

        $storedContacts = Contact::all();

        $storedEmails = Email::all();

        // Assert
        $this->assertDatabaseHas('emails', [
            'id' => 1,
            'contact_id' => $storedContacts[0]->id,
            'email' => $contacts['contacts'][0]['emails'][0],
            'status' => 0
        ]);
        $this->assertDatabaseHas('emails', [
            'id' => 2,
            'contact_id' => $storedContacts[0]->id,
            'email' => $contacts['contacts'][0]['emails'][1],
            'status' => 0
        ]);
        $this->assertDatabaseHas('emails', [
            'id' => 3,
            'contact_id' => $storedContacts[1]->id,
            'email' => $contacts['contacts'][1]['emails'][0],
            'status' => 0
        ]);
        $this->assertDatabaseHas('emails', [
            'id' => 4,
            'contact_id' => $storedContacts[1]->id,
            'email' => $contacts['contacts'][1]['emails'][1],
            'status' => 0
        ]);
        $this->assertEquals($contacts['contacts'][0]['emails'][0], $storedEmails[0]->email);
        $this->assertEquals($contacts['contacts'][0]['emails'][1], $storedEmails[1]->email);
        $this->assertEquals($contacts['contacts'][1]['emails'][0], $storedEmails[2]->email);
        $this->assertEquals($contacts['contacts'][1]['emails'][1], $storedEmails[3]->email);
    }
}
