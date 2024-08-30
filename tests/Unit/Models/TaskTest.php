<?php
use App\Models\Task;
use App\Models\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_belongs_to_project()
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);

        $this->assertEquals($project->id, $task->project->id);
    }

    public function test_task_status_is_valid()
    {
        $task = Task::factory()->create(['status' => 'todo']);

        $this->assertTrue(in_array($task->status, ['todo', 'in-progress', 'done']));
    }
}
