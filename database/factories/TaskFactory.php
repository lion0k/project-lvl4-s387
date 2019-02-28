<?php

use Faker\Generator as Faker;

$factory->define(SimpleTaskManager\Task::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'status_id' => factory(SimpleTaskManager\TaskStatus::class)->create()->id,
        'creator_id' => factory(SimpleTaskManager\User::class)->create()->id,
        'assignedTo_id' => factory(SimpleTaskManager\User::class)->create()->id
    ];
});
