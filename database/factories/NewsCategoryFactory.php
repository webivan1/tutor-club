<?php

use Faker\Generator as Faker;
use App\Entity\Media\Category;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'slug' => $faker->unique()->slug(),
        'title' => $faker->name(),
        'description' => $faker->text(50),
        'heading' => $faker->text(50),
        'content' => $faker->text(50),
        'status' => Category::STATUS_ACTIVE,
    ];
});
