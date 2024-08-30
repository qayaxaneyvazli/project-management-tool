<?php
namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $task = $this->find($id);
        $task->update($data);
        return $task;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function getTasksByProject($projectId)
    {
        return $this->model->where('project_id', $projectId)->get();
    }
}
