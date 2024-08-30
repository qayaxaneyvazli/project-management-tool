<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected $model;

    public function __construct(Project $model)
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
        $project = $this->find($id);
        $project->update($data);
        return $project;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function getProjectWithTasks($id)
    {

        return $this->model->with('tasks')->findOrFail($id);
    }
    public function search($name)
    {
        return $this->model->where('name', 'like', "%$name%")->get();
    }
}
