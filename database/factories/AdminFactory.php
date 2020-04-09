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
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'role' => 1,
        'permitted_ip' => null
    ];
});
