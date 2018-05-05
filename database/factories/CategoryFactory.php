<?php

use Faker\Generator as Faker;
use App\Entity\Category;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name(),
        'slug' => $faker->unique()->slug(),
        'title' => $faker->text(80),
        'description' => $faker->text(150),
        'content' => $faker->text(280),
        'parent_id' => null
    ];
});
