<?php

namespace Tests\Feature;

use SimpleTaskManager\TaskStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use SimpleTaskManager\User;
use Illuminate\Support\Facades\Hash;

class TaskStatusTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $password = Hash::make('you know nothing');

        $userData = [
            'name' => 'John Snow',
            'email' => 'john@winterfell.com',
            'password' => $password,
        ];
        $this->user = factory(User::class)->create($userData);
    }

    public function testGetTaskStatusesListForm()
    {
        $this->actingAs($this->user)
             ->get(route('taskstatuses.index'))
             ->assertStatus(Response::HTTP_OK);
    }

    public function testGetTaskStatusesCreateForm()
    {
        $this->actingAs($this->user)
             ->get(route('taskstatuses.create'))
             ->assertStatus(Response::HTTP_OK);
    }

    public function testGetTaskStatusesUpdateForm()
    {
        $status = factory(TaskStatus::class)->create();
        $data['id'] = $status->id;
        $this->actingAs($this->user)
             ->get(route('taskstatuses.update', $data))
             ->assertStatus(Response::HTTP_OK);
    }

    public function testCreateNewTaskStatus()
    {
        $data['name'] = 'test';
        $this->actingAs($this->user)->post(route('taskstatuses.store'), $data);
        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testUpdateTaskStatus()
    {
        $newStatus['name'] = 'test';
        $status = factory(TaskStatus::class)->create();
        $this->actingAs($this->user)->patch(route('taskstatuses.update', ['id' => $status->id]), $newStatus);
        $this->assertDatabaseHas('task_statuses', $newStatus);
    }

    public function testDeleteTaskStatus()
    {
        $status = factory(TaskStatus::class)->create();
        $this->actingAs($this->user)->delete(route('taskstatuses.destroy', ['id' => $status->id]));
        $this->assertDatabaseMissing('task_statuses', ['name' => $status->name]);
    }
}
