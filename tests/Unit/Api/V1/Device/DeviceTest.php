<?php

namespace Tests\Unit\Api\V1\Device;

use App\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itCanStoreADevice()
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
        $storedDevice = Device::create(['device_id' => $contacts['device_id']]);

        // Assert
        $this->assertInstanceOf(Device::class, $storedDevice);
        $this->assertDatabaseHas('devices', [
            'id' => 1,
            'device_id' => $contacts['device_id']
        ]);
        $this->assertEquals($contacts['device_id'], $storedDevice->device_id);
    }
}
