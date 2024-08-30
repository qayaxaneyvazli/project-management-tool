<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollection;
use App\Services\TaskService;
use App\Events\TaskUpdated;
use Pusher\Pusher;
class TaskController extends Controller
{

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return new TaskCollection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());
        broadcast(new TaskUpdated($task))->toOthers();
        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = $this->taskService->getTaskById($id);
        return new TaskResource($task);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = $this->taskService->updateTask($id, $request->validated());
        broadcast(new TaskUpdated($task))->toOthers();
        return new TaskResource($task);
    }

public function destroy(Task $task)
{
    $projectId = $task->project_id;
    $task->delete();
      //Broadcast(new TaskUpdated($task))->toOthers();
     return new TaskResource($task);
}

    public function updateStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|in:todo,in-progress,done'
        ]);

        $task = $this->taskService->updateTaskStatus($id, $request->status);
        return new TaskResource($task);
    }
}
