<?php

use App\Models\Project;
use App\Models\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_can_have_tasks()
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);

        $this->assertTrue($project->tasks->contains($task));
    }

    public function test_deleting_project_deletes_associated_tasks()
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);

        $project->delete();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
