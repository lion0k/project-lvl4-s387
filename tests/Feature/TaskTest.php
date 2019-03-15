<?php

namespace Tests\Feature;

use SimpleTaskManager\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use SimpleTaskManager\User;
use Illuminate\Support\Facades\Hash;
use SimpleTaskManager\TaskStatus;
use SimpleTaskManager\Tag;

class TaskTest extends TestCase
{
    use RefreshDatabase;

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

    public function testGetTaskListForm()
    {
        $this->actingAs($this->user)
             ->get(route('tasks.index'))
             ->assertStatus(Response::HTTP_OK);
    }

    public function testGetTaskCreateForm()
    {
        $this->actingAs($this->user)
             ->get(route('tasks.create'))
             ->assertStatus(Response::HTTP_OK);
    }

    public function testGetTaskUpdateForm()
    {
        $task = factory(Task::class)->create();
        $data['id'] = $task->id;
        $this->actingAs($this->user)
             ->get(route('tasks.update', $data))
             ->assertStatus(Response::HTTP_OK);
    }

    public function testCreateNewTask()
    {
        $data = [
            'name' => 'test',
            'description' => 'test',
            'status_id' => 1,
            'creator_id' => $this->user->id,
            'assignedTo_id' => $this->user->id
        ];

        $this->actingAs($this->user)
            ->post(route('tasks.store'), $data)
            ->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testUpdateTask()
    {
        $data = [
            'name' => 'test',
            'description' => 'test',
            'status_id' => 1,
            'creator_id' => $this->user->id,
            'assignedTo_id' => $this->user->id
        ];

        $task = factory(Task::class)->create();
        $this->actingAs($this->user)
            ->patch(route('tasks.update', ['id' => $task->id]), $data)
            ->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testDeleteTask()
    {
        $task = factory(Task::class)->create();
        $this->actingAs($this->user)
            ->delete(route('tasks.destroy', ['id' => $task->id]))
            ->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseMissing('tasks', ['name' => $task->name]);
    }

    public function testFilterTaskByCreator()
    {
        $countTask = 3;
        $countTaskByCreator = 2;
        factory(Task::class, $countTask)->create();

        $response1 = $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(Response::HTTP_OK);
        $this->assertCount($countTask, $response1->original->getData()['tasks']);

        factory(Task::class, $countTaskByCreator)->create(['creator_id' => $this->user->id]);
        $response2 = $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(Response::HTTP_OK);
        $this->assertCount(
            $countTask + $countTaskByCreator,
            $response2->original->getData()['tasks']
        );

        $response3 = $this->actingAs($this->user)
            ->call('GET', route('tasks.index'), ['only_me' => 'on'])
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount($countTaskByCreator, $response3->original->getData()['tasks']);
    }

    public function testFilterTaskAssignedTo()
    {
        $countTask = 3;
        $countTaskAssignedTo = 2;
        factory(Task::class, $countTask)->create();

        $response1 = $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(Response::HTTP_OK);
        $this->assertCount($countTask, $response1->original->getData()['tasks']);

        factory(Task::class, $countTaskAssignedTo)->create(['assignedTo_id' => $this->user->id]);
        $response2 = $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(Response::HTTP_OK);
        $this->assertCount(
            $countTask + $countTaskAssignedTo,
            $response2->original->getData()['tasks']
        );

        $response3 = $this->actingAs($this->user)
            ->call('GET', route('tasks.index'), ['assignedTo_id' => "{$this->user->id}"])
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount($countTaskAssignedTo, $response3->original->getData()['tasks']);
    }

    public function testFilterTaskByStatus()
    {
        $countTask = 3;
        $countTaskBySpecialStatus = 2;
        factory(Task::class, $countTask)->create();

        $response1 = $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(Response::HTTP_OK);
        $this->assertCount($countTask, $response1->original->getData()['tasks']);

        $status = factory(TaskStatus::class)->create();

        factory(Task::class, $countTaskBySpecialStatus)->create(['status_id' => $status->id]);
        $response2 = $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(Response::HTTP_OK);
        $this->assertCount(
            $countTask + $countTaskBySpecialStatus,
            $response2->original->getData()['tasks']
        );

        $response3 = $this->actingAs($this->user)
            ->call('GET', route('tasks.index'), ['status_id' => "$status->id"])
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount($countTaskBySpecialStatus, $response3->original->getData()['tasks']);
    }

    public function testFilterTaskByTag()
    {
        $countTask = 3;
        factory(Task::class, $countTask)->create();

        $response1 = $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(Response::HTTP_OK);
        $this->assertCount($countTask, $response1->original->getData()['tasks']);

        $tag = factory(Tag::class)->create(['name' => 'test']);
        $data = [
            'name' => 'test',
            'description' => 'test',
            'status_id' => 1,
            'creator_id' => $this->user->id,
            'assignedTo_id' => $this->user->id
        ];

        $dataWithTag = array_merge($data, ['tags' => $tag->name]);

        $this->actingAs($this->user)
            ->post(route('tasks.store'), $dataWithTag)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('tasks', $data);
        $this->assertDatabaseHas('tags', ['name' => "{$tag->name}"]);
        $this->assertDatabaseHas('task_tag', ['tag_id' => "{$tag->id}"]);

        $response2 = $this->actingAs($this->user)
            ->get(route('tasks.index'))
            ->assertStatus(Response::HTTP_OK);
        $this->assertCount($countTask + 1, $response2->original->getData()['tasks']);

        $response3 = $this->actingAs($this->user)
            ->call('GET', route('tasks.index'), ['tag_id' => "{$tag->id}"])
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(1, $response3->original->getData()['tasks']);
    }

    public function testFilterTaskByMultiplyTag()
    {
        $tag = factory(Tag::class)->create(['name' => 'test, bug']);
        $data = [
            'name' => 'test',
            'description' => 'test',
            'status_id' => 1,
            'creator_id' => $this->user->id,
            'assignedTo_id' => $this->user->id
        ];

        $dataWithTag = array_merge($data, ['tags' => $tag->name]);
        $this->actingAs($this->user)
            ->post(route('tasks.store'), $dataWithTag)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('tasks', $data);
        $this->assertDatabaseHas('tags', ['name' => "{$tag->name}"]);

        $tag2 = factory(Tag::class)->create(['name' => 'solution, fix']);
        $data2 = [
            'name' => 'test',
            'description' => 'test',
            'status_id' => 1,
            'creator_id' => $this->user->id,
            'assignedTo_id' => $this->user->id,
            'tags' => $tag2->name
        ];
        $this->actingAs($this->user)
            ->post(route('tasks.store'), $data2)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('tags', ['name' => "{$tag2->name}"]);

        $tags = Tag::has('tasks')->get();
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('task_tag', ['tag_id' => $tag->id]);

            $response2 = $this->actingAs($this->user)
                ->call('GET', route('tasks.index'), ['tag_id' => "{$tag->id}"])
                ->assertStatus(Response::HTTP_OK);

            $this->assertCount(1, $response2->original->getData()['tasks']);
        }
    }
}
