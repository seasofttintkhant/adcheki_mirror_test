<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Device;
use App\Models\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    $data = [
        'emails' => [
            $faker->email
        ],
        'name' => $faker->name,
        'phones' => [
            $faker->phoneNumber
        ]
    ];
    return [
        'device_id' => factory(Device::class),
        'data' => json_encode($data)
    ];
});
