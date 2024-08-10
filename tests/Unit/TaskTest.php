<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\TaskController;
use App\Http\Requests\TaskRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_successful_response()
    {
        Task::factory()->count(3)->create();

        $controller = new TaskController();
        $response = $controller->index();

        $this->assertEquals(200, $response->status());
        $this->assertCount(3, $response->getData()->data);
    }

    public function test_store_creates_task()
    {
        $controller = new TaskController();

        $request = TaskRequest::create('/api/tasks', 'POST', [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'completed_at' => now(),
        ]);

        $request->setContainer(app())->validateResolved();

        $response = $controller->store($request);

        $this->assertEquals(201, $response->status());
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    public function test_show_returns_task()
    {
        $task = Task::factory()->create();

        $controller = new TaskController();
        $response = $controller->show($task->id);

        $this->assertEquals(200, $response->status());
        $this->assertEquals($task->title, $response->getData()->data->title);
    }

    public function test_update_updates_task()
    {
        $task = Task::factory()->create();

        $controller = new TaskController();

        // Create an actual TaskRequest and manually validate it
        $request = TaskRequest::create('/api/tasks/' . $task->id, 'PUT', [
            'title' => 'Updated Task',
            'description' => $task->description,
            'completed_at' => now(),
        ]);

        $request->setContainer(app())->validateResolved();

        $response = $controller->update($request, $task->id);

        $this->assertEquals(200, $response->status());
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Task']);
    }

    public function test_destroy_deletes_task()
    {
        $task = Task::factory()->create();

        $controller = new TaskController();
        $response = $controller->destroy($task->id);

        $this->assertEquals(200, $response->status());
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
