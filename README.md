# Project Management Tool

## Table of Contents
- [Project Management Tool](#project-management-tool)
  - [Table of Contents](#table-of-contents)
  - [Project Overview](#project-overview)
  - [Features](#features)
  - [Technologies Used](#technologies-used)
  - [Project Structure](#project-structure)
  - [Setup and Installation](#setup-and-installation)
  - [Docker Setup](#docker-setup)
  - [API Endpoints](#api-endpoints)
  - [Database Schema](#database-schema)
  - [Testing](#testing)
  - [Deployment](#deployment)
  - [Future Enhancements](#future-enhancements)
  - [Contributing](#contributing)
  - [License](#license)

## Project Overview
This Project Management Tool is a web-based application designed to help teams organize, track, and manage their projects and tasks efficiently. It provides a user-friendly interface for creating projects, assigning tasks, and monitoring progress.

## Features
- User authentication and authorization
- Create, read, update, and delete (CRUD) operations for projects
- CRUD operations for tasks within projects
- Real-time updates using WebSockets
- Task status tracking (todo, in-progress, done)
- RESTful API for integration with other services

## Technologies Used

- Backend: PHP 8.1 with Laravel 9 framework
- Frontend: HTML, CSS, JavaScript (with Laravel Mix for asset compilation)
- Node.js and npm for frontend dependency management and build  processes.This  project requires Node.js version 20 or higher. Please make sure you have a compatible version of Node.js installed before attempting to run the application.
- Database: MySQL 5.7
- Caching: Redis
- WebSockets: Laravel Echo and Pusher
- Containerization: Docker
- Version Control: Git

## Project Structure
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Repositories/
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   └── js/
├── routes/
├── tests/
├── docker/
└── docker-compose.yml

## Setup and Installation
1. Clone the repository:
git clone https://github.com/qayaxaneyvazli/project-management-tool


2. Navigate to the project directory:

    cd project-management-tool

3. Install PHP dependencies:

      composer install

4. Install Node.js dependencies:
    
    npm install

5. Copy the `.env.example` file to `.env` and configure your environment variables:

   cp .env.example .env
6. Generate an application key: 
   
   php artisan key:generate  

7. Run database migrations:
 
     php artisan migrate

8. (Optional) Seed the database with sample data:
 
    php artisan db:seed


9. Compile frontend assets:
    
    npm run dev

10. Start the development server:
   
      php artisan serve

## Docker Setup
This project is dockerized for easy setup and deployment. To run the project using Docker:

1. Ensure Docker and Docker Compose are installed on your system.
2. Build and start the containers:
   
   docker-compose up -d --build





3. The application should now be accessible at `http://localhost`.

For more details on Docker setup, refer to the `Dockerfile` and `docker-compose.yml` in the project root.

## API Endpoints
- GET `/api/projects`: List all projects
- POST `/api/projects`: Create a new project
- GET `/api/projects/{id}`: Get details of a specific project
- PUT `/api/projects/{id}`: Update a project
- DELETE `/api/projects/{id}`: Delete a project
- GET `/api/projects/{id}/tasks`: List tasks for a specific project
- POST `/api/tasks`: Create a new task
- PUT `/api/tasks/{id}`: Update a task
- DELETE `/api/tasks/{id}`: Delete a task

For detailed API documentation, refer to the [API Documentation](link-to-api-docs).

## Database Schema
The database consists of two main tables:
1. `projects` table:
- id (primary key)
- name
- description
- created_at
- updated_at

2. `tasks` table:
- id (primary key)
- project_id (foreign key referencing projects table)
- name
- description
- status (enum: todo, in-progress, done)
- created_at
- updated_at

## Testing
This project uses PHPUnit for testing. To run the tests:
php artisan test
Copy
For more detailed testing information, see the [Testing Documentation](link-to-testing-docs).

## Deployment
[Include instructions or links to deployment guides for various platforms]

## Future Enhancements
- Implement user roles and permissions
- Add file attachment functionality to tasks
- Integrate with popular project management tools
- Implement a dashboard with project analytics

## Contributing
Contributions are welcome! Please feel free to submit a Pull Request.

## License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/l
