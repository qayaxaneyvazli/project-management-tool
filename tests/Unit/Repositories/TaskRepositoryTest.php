<?php

namespace Tests\Unit\Repositories;

use App\Models\Task;
use App\Models\Project;
use App\Repositories\Eloquent\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository(new Task());
    }

    public function test_can_get_all_tasks()
    {
        Task::factory()->count(3)->create();

        $tasks = $this->taskRepository->all();

        $this->assertCount(3, $tasks);
    }

    public function test_can_create_task()
    {
        $project = Project::factory()->create();
        $taskData = [
            'name' => 'New Task',
            'description' => 'Task Description',
            'status' => 'todo',
            'project_id' => $project->id
        ];

        $task = $this->taskRepository->create($taskData);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('New Task', $task->name);
        $this->assertEquals('todo', $task->status);
    }

    public function test_can_find_task_by_id()
    {
        $task = Task::factory()->create();

        $foundTask = $this->taskRepository->find($task->id);

        $this->assertEquals($task->id, $foundTask->id);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create();
        $updatedData = ['name' => 'Updated Task Name'];

        $updatedTask = $this->taskRepository->update($task->id, $updatedData);

        $this->assertEquals('Updated Task Name', $updatedTask->name);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $result = $this->taskRepository->delete($task->id);

        $this->assertEquals(1, $result);   
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_can_get_tasks_by_project()
    {
        $project = Project::factory()->create();
        Task::factory()->count(3)->create(['project_id' => $project->id]);
        Task::factory()->create(); // Another task not related to the project

        $projectTasks = $this->taskRepository->getTasksByProject($project->id);

        $this->assertCount(3, $projectTasks);
    }

    public function test_can_get_tasks_by_status()
    {
        Task::factory()->count(2)->create(['status' => 'todo']);
        Task::factory()->count(3)->create(['status' => 'in-progress']);

        $todoTasks = $this->taskRepository->getTasksByStatus('todo');

        $this->assertCount(2, $todoTasks);
    }
}
