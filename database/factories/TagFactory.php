<?php

use Faker\Generator as Faker;

$factory->define(SimpleTaskManager\Tag::class, function (Faker $faker) {
    return [
       'name' => $faker->name
    ];
});
