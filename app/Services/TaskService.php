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
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return null;
        }

        return $task;
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



    public function getProjectTasks($projectId)
    {
        return $this->taskRepository->getByProjectId($projectId);
    }
}
