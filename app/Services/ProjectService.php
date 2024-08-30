<?php

namespace App\Services;

use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectService
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getAllProjects()
    {
        return $this->projectRepository->all();
    }

    public function createProject(array $data)
    {

        return $this->projectRepository->create($data);
    }

    public function getProjectWithTasks($id)
    {
        return $this->projectRepository->getProjectWithTasks($id);
    }
    public function getProjectById($id)
    {
        return $this->projectRepository->find($id);
    }

    public function updateProject($id, array $data)
    {
        return $this->projectRepository->update($id, $data);
    }
    public function deleteProject($id)
    {
        return $this->projectRepository->delete($id);
    }

    public function searchProjects($name)
    {
        return $this->projectRepository->search($name);
    }

}
