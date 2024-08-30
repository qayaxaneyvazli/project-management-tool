@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Projects</h1>
        <button class="btn btn-success" id="add-project-btn">
            <i class="fas fa-plus"></i> Add Project
        </button>
    </div>

    <div class="input-group mb-4">
        <input type="text" id="searchInput" class="form-control" placeholder="Search projects...">


    </div>

    <div id="project-list" class="row">

    </div>

    <!-- Add/Edit Project Modal -->
    <div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectModalLabel">Add/Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="projectForm">
                        <input type="hidden" id="projectId">
                        <div class="mb-3">
                            <label for="projectName" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="projectName" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="projectDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveProjectBtn">Save Project</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>


        document.addEventListener('DOMContentLoaded', function () {
            loadProjects();

            document.getElementById('searchInput').addEventListener('input', function() {
        filterProjects(this.value);
    });

            // Add Project button click event
            document.getElementById('add-project-btn').addEventListener('click', function() {
                document.getElementById('projectId').value = '';
                document.getElementById('projectName').value = '';
                document.getElementById('projectDescription').value = '';
                document.getElementById('projectModalLabel').textContent = 'Add Project';
                new bootstrap.Modal(document.getElementById('projectModal')).show();
            });

            // Save Project button click event
            document.getElementById('saveProjectBtn').addEventListener('click', saveProject);
        });

        function loadProjects() {
    axios.get('/api/projects')
        .then(function (response) {
            allProjects = response.data.data; // Tüm projeleri saklıyoruz
            renderProjects(allProjects);
        })
        .catch(function (error) {
            console.error('Error fetching projects:', error);
            showAlert('Error loading projects. Please try again.', 'danger');
        });
}

function renderProjects(projects) {
    const projectList = document.getElementById('project-list');
    projectList.innerHTML = '';

    projects.forEach(function (project) {
        const col = document.createElement('div');
        col.className = 'col-md-4 mb-4';

        const card = `
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${project.name}</h5>
                    <p class="card-text">${project.description ? project.description : 'No description available.'}</p>
                    <div class="mt-auto">
                        <a href="/projects/${project.id}" class="btn btn-primary">View Details</a>
                        <button class="btn btn-warning" onclick="editProject(${project.id})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteProject(${project.id})">Delete</button>
                    </div>
                </div>
            </div>
        `;

        col.innerHTML = card;
        projectList.appendChild(col);
    });
}

function filterProjects(searchTerm) {
    searchTerm = searchTerm.toLowerCase();
    const filteredProjects = allProjects.filter(project =>
        project.name.toLowerCase().includes(searchTerm)
    );
    renderProjects(filteredProjects);
}
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value;
    filterProjects(searchTerm);
}

        function saveProject() {
            const projectId = document.getElementById('projectId').value;
            const projectName = document.getElementById('projectName').value;
            const projectDescription = document.getElementById('projectDescription').value;

            const method = projectId ? 'put' : 'post';
            const url = projectId ? `/api/projects/${projectId}` : '/api/projects';

            axios[method](url, {
                name: projectName,
                description: projectDescription
            })
            .then(function (response) {
                showAlert(projectId ? 'Project updated successfully' : 'Project added successfully', 'success');
                bootstrap.Modal.getInstance(document.getElementById('projectModal')).hide();
                loadProjects();
            })
            .catch(function (error) {
                console.error('Error saving project:', error);
                showAlert('Error saving project. Please try again.', 'danger');
            });
        }

        function editProject(projectId) {
            axios.get(`/api/projects/${projectId}`)
                .then(function (response) {
                    const project = response.data.data;
                    document.getElementById('projectId').value = project.id;
                    document.getElementById('projectName').value = project.name;
                    document.getElementById('projectDescription').value = project.description;
                    document.getElementById('projectModalLabel').textContent = 'Edit Project';
                    new bootstrap.Modal(document.getElementById('projectModal')).show();
                })
                .catch(function (error) {
                    console.error('Error fetching project details:', error);
                    showAlert('Error loading project details. Please try again.', 'danger');
                });
        }

        function deleteProject(projectId) {
            if (confirm('Are you sure you want to delete this project?')) {
                axios.delete(`/api/projects/${projectId}`)
                    .then(function (response) {
                        showAlert('Project deleted successfully', 'success');
                        loadProjects();
                    })
                    .catch(function (error) {
                        console.error('Error deleting project:', error);
                        showAlert('Error deleting project. Please try again.', 'danger');
                    });
            }
        }

        function showAlert(message, type) {
            const alertPlaceholder = document.createElement('div');
            alertPlaceholder.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            document.querySelector('.container').prepend(alertPlaceholder);

            setTimeout(() => {
                alertPlaceholder.remove();
            }, 5000);
        }
    </script>
@endsection
<script src="{{ asset('js/app.js') }}"></script>
