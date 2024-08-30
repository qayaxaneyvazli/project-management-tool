<?php

use App\Services\ProjectService;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ProjectServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $projectService;
    protected $projectRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectRepository = Mockery::mock(ProjectRepositoryInterface::class);

        $this->projectService = new ProjectService($this->projectRepository);
    }

    public function test_get_all_projects()
    {
        $projects = Project::factory()->count(3)->make();
        $this->projectRepository->shouldReceive('all')->once()->andReturn($projects);

        $result = $this->projectService->getAllProjects();

        $this->assertCount(3, $result);
    }

    // Similar tests for createProject, updateProject, deleteProject, etc.

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
