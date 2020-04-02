<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Email;
use App\Models\Contact;
use Faker\Generator as Faker;

$factory->define(Email::class, function (Faker $faker) {
    return [
        'contact_id' => factory(Contact::class),
        'email' => function (array $email) {
            $contact = Contact::findOrFail($email['contact_id']);
            $contact = json_decode($contact->data, true);
            return $contact['emails'][0];
        },
        'status' => $faker->boolean
    ];
});
