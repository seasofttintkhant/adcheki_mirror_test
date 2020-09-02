<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin;
use Faker\Generator as Faker;

$factory->define(Admin::class, function (Faker $faker) {
    $lastRecord = Admin::latest()->first();
    $nextId = 1;
    if ($lastRecord) {
        $nextId = $lastRecord->id + 1;
    }
    return [
        'operator_id' => '1' . str_pad($nextId, 9, '0', STR_PAD_LEFT),
        'login_id' => $faker->name,
        'password' => \Hash::make("p@ssw0rd"), // password
        'role' => 1,
        'permitted_ip' => null
    ];
});
