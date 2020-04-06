<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Device;
use App\Models\EmailResult;
use Faker\Generator as Faker;

$factory->define(EmailResult::class, function (Faker $faker) {
    $results = [
        $faker->safeEmail => [
            'valid' => $faker->boolean,
            'exist' => $faker->boolean
        ],
        $faker->safeEmail => [
            'valid' => $faker->boolean,
            'exist' => $faker->boolean
        ],
    ];

    return [
        'device_id' => factory(Device::class)->create()->device_id,
        'result' => $results
    ];
});
