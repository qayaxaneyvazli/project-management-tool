<?php
namespace App\Services;

use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks()
    {
        return $this->taskRepository->all();
    }

    public function getTaskById($id)
    {
        return $this->taskRepository->find($id);
    }

    public function createTask(array $data)
    {
        return $this->taskRepository->create($data);
    }

    public function updateTask($id, array $data)
    {
        return $this->taskRepository->update($id, $data);
    }

    public function deleteTask($id)
    {
        return $this->taskRepository->delete($id);
    }

    public function updateTaskStatus($id, $status)
    {
        $task = $this->taskRepository->find($id);
        $task->status = $status;
        return $this->taskRepository->update($id, $task->toArray());
    }
}
