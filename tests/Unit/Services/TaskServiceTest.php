<?php

namespace Tests\Unit;

use App\Services\TaskService;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Tests\TestCase;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $taskService;
    protected $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $this->taskService = new TaskService($this->taskRepository);
    }


    public function test_get_task_by_id()
    {
        $taskId = 1;
        $task = new Task(['id' => $taskId, 'name' => 'Test Task', 'project_id' => 1]);

        $this->taskRepository->shouldReceive('find')
            ->once()
            ->with($taskId)
            ->andReturn($task);

        $result = $this->taskService->getTaskById($taskId);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('Test Task', $result->name);
        $this->assertEquals(1, $result->project_id);

     
    }

    public function test_get_task_by_id_returns_null_for_non_existent_task()
    {
        $taskId = 999; // Var olmayan bir task ID'si

        $this->taskRepository->shouldReceive('find')
            ->once()
            ->with($taskId)
            ->andReturn(null);

        $result = $this->taskService->getTaskById($taskId);

        $this->assertNull($result);
    }



    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
