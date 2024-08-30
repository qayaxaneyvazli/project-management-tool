<?php
use App\Repositories\Eloquent\ProjectRepository;
use App\Models\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $projectRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectRepository = new ProjectRepository(new Project());
    }

    public function test_get_all_projects()
    {
        Project::factory()->count(3)->create();

        $projects = $this->projectRepository->all();

        $this->assertCount(3, $projects);
    }

    public function test_create_project()
    {
        $projectData = ['name' => 'Test Project', 'description' => 'Test Description'];

        $project = $this->projectRepository->create($projectData);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Test Project', $project->name);
    }

    // Similar tests for update, delete, and other repository methods
}
