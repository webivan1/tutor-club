<?php

use Faker\Generator as Faker;

$factory->define(\App\Entity\Chat\Messages::class, function (Faker $faker) {
    return [
        'dialog_id' => $dialog = \App\Entity\Chat\Dialogs::all()->random()->id,
        'user_id' => \App\Entity\Chat\DialogUsers::where('dialog_id', $dialog)->get()->random()->user_id,
        'message' => $faker->text($faker->numberBetween(20, 200)),
        'created_at' => $faker->time('Y-m-d H:i:s'),
        'updated_at' => $faker->time('Y-m-d H:i:s')
    ];
});
