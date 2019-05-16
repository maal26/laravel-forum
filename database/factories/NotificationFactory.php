<?php

use App\User;
use Faker\Generator as Faker;
use Illuminate\Notifications\DatabaseNotification;
use Ramsey\Uuid\Uuid;

$factory->define(DatabaseNotification::class, function (Faker $faker) {
    return [
        'id'              => Uuid::uuid4()->toString(),
        'type'            => 'App\Notifications\ThreadWasUpdated',
        'notifiable_type' => 'App\User',
        'notifiable_id'   => function () {
            return auth()->user() ?: factory(User::class)->create()->id;
        },
        'data'            => ['foo' => 'barr'],
    ];
});
