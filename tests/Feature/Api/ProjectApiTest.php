<?php

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_get_all_projects()
    {
        Project::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_project()
    {
        $projectData = ['name' => 'New Project', 'description' => 'Project Description'];

        $response = $this->actingAs($this->user)->postJson('/api/projects', $projectData);

        $response->assertStatus(201)
            ->assertJsonFragment($projectData);
    }

    public function test_can_update_project()
    {
        $project = Project::factory()->create();
        $updateData = ['name' => 'Updated Project'];

        $response = $this->actingAs($this->user)->putJson("/api/projects/{$project->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);
    }

    public function test_can_delete_project()
    {
        $project = Project::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/projects/{$project->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
