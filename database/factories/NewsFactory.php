<?php

use Faker\Generator as Faker;
use App\Entity\Media\News;
use App\Entity\Media\Category;
use App\Entity\Files;
use Carbon\Carbon;

$factory->define(News::class, function (Faker $faker) {
    $file = Files::where('source', 'test')->where('source_id', 0)->firstOr(['id'], function () {
        return Files::select(['id'])->get()->random();
    });

    return [
        'heading' => $faker->text(mt_rand(50, 200)),
        'slug' => $faker->unique()->slug(),
        'category_id' => Category::where('status', Category::STATUS_ACTIVE)->get()->random()->id,
        'content' => $faker->text(mt_rand(500, 4000)),
        'file_id' => $file->id ?? 0,
        'title' => $faker->text(150),
        'description' => $faker->text(200),
        'lang' => array_random(\LaravelLocalization::getSupportedLanguagesKeys()),
        'status' => News::STATUS_ACTIVE,
        'published_at' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
    ];
});
