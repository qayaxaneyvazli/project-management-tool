<?php


use App\Models\Test;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Project;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create();
    }

    public function test_cannot_create_task_with_empty_name()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tasks', [
            'name' => '',
            'project_id' => $this->project->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_set_invalid_task_status()
    {
        $task = Task::factory()->create(['project_id' => $this->project->id]);

        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
            'status' => 'invalid_status'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_cannot_assign_task_to_non_existent_project()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tasks', [
            'name' => 'Test Task',
            'project_id' => 999
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['project_id']);
    }

    public function test_cannot_update_task_of_non_existent_project()
    {
        $task = Task::factory()->create(['project_id' => $this->project->id]);

        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
            'project_id' => 999
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['project_id']);
    }
}
