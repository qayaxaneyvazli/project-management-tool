<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\TaskCollection;
use App\Models\Project;
class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function search(Request $request)
    {
        $name = $request->input('search');
        $projects = $this->projectService->searchProjects($name);

        return view('projects.search', compact('projects'));
    }

    public function index()
    {
        $projects = $this->projectService->getAllProjects();
        return new ProjectCollection($projects);
    }

    public function store(ProjectRequest $request)
    {
        $project = $this->projectService->createProject($request->validated());
        return new ProjectResource($project);
    }

    public function show($id)
    {
        $project = $this->projectService->getProjectWithTasks($id);
        return new ProjectResource($project);
    }

    public function update(ProjectRequest $request, $id)
    {
        $project = $this->projectService->updateProject($id, $request->validated());
        return new ProjectResource($project);
    }

    public function destroy($id)
    {
        $this->projectService->deleteProject($id);
        return response()->json(null, 204);
    }

    public function getTasks($id)
    {
        $tasks = $this->projectService->getProjectWithTasks($id);
        return new TaskResource($tasks);
    }
}
