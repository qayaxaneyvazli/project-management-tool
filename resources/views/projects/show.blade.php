@extends('layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 id="project-name" class="display-4">Loading project...</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Projects
            </a>
        </div>
    </div>

    <div class="card mb-4 bg-light">
        <div class="card-body">
            <h5 class="card-title text-primary">Project Description</h5>
            <p id="project-description" class="card-text">Loading description...</p>
        </div>
    </div>

    <h2 class="h3 mb-4 text-primary">
        <i class="fas fa-tasks"></i> Tasks
        <button class="btn btn-success btn-sm float-end" id="add-task-btn">
            <i class="fas fa-plus"></i> Add Task
        </button>
    </h2>
    <div>
        <h2>Filter By Status<h2>
        <select id="statusFilter" class="form-select form-select-sm me-2" style="width: auto; display: inline-block;">
            <option value="all">All Status</option>
            <option value="todo">To Do</option>
            <option value="in-progress">In Progress</option>
            <option value="done">Done</option>
        </select>

    </div>
    <hr>



    <div id="task-list" class="row">
        <!-- Tasks will be listed here -->
    </div>
</div>

<!-- Modal for adding tasks -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTaskForm">
                    <div class="mb-3">
                        <label for="taskName" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="taskName" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="taskDescription"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="taskStatus" class="form-label">Status</label>
                        <select class="form-control" id="taskStatus">
                            <option value="todo">To Do</option>
                            <option value="in-progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addTask()">Add Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for editing tasks -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" id="editTaskId">
                    <div class="mb-3">
                        <label for="editTaskName" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="editTaskName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editTaskDescription"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskStatus" class="form-label">Status</label>
                        <select class="form-control" id="editTaskStatus">
                            <option value="todo">To Do</option>
                            <option value="in-progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateTaskBtn">Update Task</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<script>
    let projectId;
function deleteTask(taskId) {
    if (confirm('Are you sure you want to delete this task?')) {
        axios.delete(`/api/tasks/${taskId}`)
            .then(function (response) {
                console.log('Task deleted successfully');
                // Remove the task from UI
                const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
                if (taskElement) {
                    taskElement.remove();
                }
                showAlert('Task deleted successfully', 'success');

                // Check if there are no more tasks
                const taskList = document.getElementById('task-list');
                if (taskList.children.length === 0) {
                    taskList.innerHTML = '<div class="col-12"><div class="alert alert-info">No tasks available for this project.</div></div>';
                }
            })
            .catch(function (error) {
                console.error('Error deleting task:', error);
                showAlert('Error deleting task. Please try again.', 'danger');
            });
    }
}

function editTask(taskId) {
    axios.get(`/api/tasks/${taskId}`)
        .then(function (response) {
            const task = response.data.data;
            document.getElementById('editTaskId').value = task.id;
            document.getElementById('editTaskName').value = task.name;
            document.getElementById('editTaskDescription').value = task.description;
            document.getElementById('editTaskStatus').value = task.status;

            var editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
            editTaskModal.show();
        })
        .catch(function (error) {
            console.error('Error fetching task details:', error);
            showAlert('Error fetching task details. Please try again.', 'danger');
        });
}



function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.querySelector('.container').prepend(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}
document.addEventListener('DOMContentLoaded', function () {
    const projectId = {{ $id }};

    // window.Echo.channel(`project.${projectId}`)
    //     .listen('TaskUpdated', (e) => {
    //         if (e.task.deleted) {
    //             const taskElement = document.querySelector(`[data-task-id="${e.task.id}"]`);
    //             if (taskElement) {
    //                 taskElement.remove();
    //             }
    //         } else {
    //             updateTaskInUI(e.task);
    //         }
    //     });

//     Pusher.logToConsole = true; // Geliştirme aşamasında faydalı, canlıya alırken kaldırın

// var pusher = new Pusher('c6e92c66b1405e5e075d', {
//   cluster: 'eu'
// });

var channel = pusher.subscribe(`project.${projectId}`);
channel.bind('TaskUpdated', function(data) {
    if (data.task.deleted) {
        const taskElement = document.querySelector(`[data-task-id="${data.task.id}"]`);
        if (taskElement) {
            taskElement.remove();
        }
    } else {
        updateTaskInUI(data.task);
    }
});


    document.getElementById('add-task-btn').addEventListener('click', function() {
        var modal = new bootstrap.Modal(document.getElementById('addTaskModal'));
        modal.show();
    });

    let allTasks = [];




    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTasks(this.value);
    });

    function loadProjectDetails() {
        axios.get(`/api/projects/${projectId}`)
            .then(function (response) {
                const project = response.data.data;
                document.getElementById('project-name').textContent = project.name;
                document.getElementById('project-description').textContent = project.description || 'No description available.';

                allTasks = project.tasks; // Tüm görevleri saklıyoruz
                renderTasks(allTasks);
            })
            .catch(function (error) {
                console.error('Error fetching project details:', error);
                showAlert('Error loading project details. Please try again.', 'danger');
            });
    }

    function renderTasks(tasks) {
        const taskList = document.getElementById('task-list');
        taskList.innerHTML = '';

        if (tasks.length === 0) {
            taskList.innerHTML = '<div class="col-12"><div class="alert alert-info">No tasks available for this project.</div></div>';
        } else {
            tasks.forEach(function (task) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-4';
                col.setAttribute('data-task-id', task.id);
                col.innerHTML = `
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-${getStatusColor(task.status)} text-white">
                            ${getStatusIcon(task.status)} ${task.status ? task.status.toUpperCase() : 'NO STATUS'}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${task.name}</h5>
                            <p class="card-text">${task.description || 'No description available.'}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <button class="btn btn-sm btn-outline-primary" onclick="editTask(${task.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(${task.id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
                taskList.appendChild(col);
            });
        }
    }

    function filterTasks(status) {
        if (status === 'all') {
            renderTasks(allTasks);
        } else {
            const filteredTasks = allTasks.filter(task => task.status === status);
            renderTasks(filteredTasks);
        }
    }

    // Initial load
    loadProjectDetails();


    window.addTask = function() {
        const taskName = document.getElementById('taskName').value;
        const taskDescription = document.getElementById('taskDescription').value;
        const taskStatus = document.getElementById('taskStatus').value;

        axios.post('/api/tasks', {
            project_id: projectId,
            name: taskName,
            description: taskDescription,
            status: taskStatus
        })
        .then(function (response) {
            console.log('Task added successfully');
            showAlert('Task added successfully', 'success');
            var modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
            modal.hide();
            // Sayfayı yenileyerek yeni task'ı göster
            window.location.reload();
        })
        .catch(function (error) {
            console.error('Error adding task:', error);
            showAlert('Error adding task. Please try again.', 'danger');
        });
    };
axios.get(`/api/projects/${projectId}`)
    .then(function (response) {
        const project = response.data.data;
        document.getElementById('project-name').textContent = project.name;
        document.getElementById('project-description').textContent = project.description || 'No description available.';

        const taskList = document.getElementById('task-list');
        taskList.innerHTML = '';

        if (project.tasks.length === 0) {
            taskList.innerHTML = '<div class="col-12"><div class="alert alert-info">No tasks available for this project.</div></div>';
        } else {
            project.tasks.forEach(function (task) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-4';
                col.setAttribute('data-task-id', task.id);
                col.innerHTML = `
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-${getStatusColor(task.status)} text-white">
                            ${getStatusIcon(task.status)} ${task.status ? task.status.toUpperCase() : 'NO STATUS'}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${task.name}</h5>
                            <p class="card-text">${task.description || 'No description available.'}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <button class="btn btn-sm btn-outline-primary" onclick="editTask(${task.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(${task.id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
                taskList.appendChild(col);
            });
        }
    })
    .catch(function (error) {
        console.error('Error fetching project details:', error);
        showAlert('Error loading project details. Please try again.', 'danger');
    });

    function getStatusColor(status) {
        switch(status) {
            case 'todo': return 'danger';
            case 'in-progress': return 'primary';
            case 'done': return 'success';
            default: return 'secondary';
        }
    }

    function getStatusIcon(status) {
        switch(status) {
            case 'todo': return '<i class="fas fa-exclamation-circle"></i>';
            case 'in-progress': return '<i class="fas fa-spinner fa-spin"></i>';
            case 'done': return '<i class="fas fa-check-circle"></i>';
            default: return '<i class="fas fa-question-circle"></i>';
        }
    }

document.getElementById('updateTaskBtn').addEventListener('click', function() {
    const taskId = document.getElementById('editTaskId').value;
    const taskName = document.getElementById('editTaskName').value;
    const taskDescription = document.getElementById('editTaskDescription').value;
    const taskStatus = document.getElementById('editTaskStatus').value;

    axios.put(`/api/tasks/${taskId}`, {
        name: taskName,
        description: taskDescription,
        status: taskStatus,
        project_id: projectId
    })
    .then(function (response) {
        showAlert('Task updated successfully', 'success');
        const editTaskModal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
        editTaskModal.hide();
        updateTaskInUI(response.data.data);
    })
    .catch(function (error) {
        console.error('Error updating task:', error);
        if (error.response && error.response.data && error.response.data.message) {
            showAlert('Error: ' + error.response.data.message, 'danger');
        } else {
            showAlert('Error updating task. Please try again.', 'danger');
        }
    });
});

function updateTaskInUI(task) {
    let taskElement = document.querySelector(`[data-task-id="${task.id}"]`);

    if (!taskElement) {
        // Yeni task ekleniyor
        taskElement = document.createElement('div');
        taskElement.className = 'col-md-4 mb-4';
        taskElement.setAttribute('data-task-id', task.id);
        document.getElementById('task-list').appendChild(taskElement);
    }

    taskElement.innerHTML = `
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-${getStatusColor(task.status)} text-white">
                ${getStatusIcon(task.status)} ${task.status ? task.status.toUpperCase() : 'NO STATUS'}
            </div>
            <div class="card-body">
                <h5 class="card-title">${task.name}</h5>
                <p class="card-text">${task.description || 'No description available.'}</p>
            </div>
            <div class="card-footer bg-transparent">
                <button class="btn btn-sm btn-outline-primary" onclick="editTask(${task.id})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(${task.id})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    `;
}

});


</script>
<script src="{{ asset('js/app.js') }}"></script>
