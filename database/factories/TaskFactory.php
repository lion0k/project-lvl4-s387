<?php

use Faker\Generator as Faker;

$getRandomStatusId = function() {
    $maxId = DB::table('task_statuses')->max('id');
    return rand(1, $maxId);
};

$factory->define(SimpleTaskManager\Task::class, function (Faker $faker) use ($getRandomStatusId) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'status_id' => $getRandomStatusId,
        'creator_id' => factory(SimpleTaskManager\User::class)->create()->id,
        'assignedTo_id' => factory(SimpleTaskManager\User::class)->create()->id
    ];
});
