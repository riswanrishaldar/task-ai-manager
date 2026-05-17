# Laravel AI-Assisted Task Management System

A production-ready Task Management System built as part of the Laravel Senior Machine Test. The application follows clean architecture principles with Repository Pattern, Service Layer, Policy-based authorization, REST APIs, responsive UI, and AI-assisted task summarization and prioritization.

This project was designed to meet the mandatory machine test requirements, including a repository-driven module structure, no direct Eloquent usage in controllers, AI integration through a dedicated service, and role-based task access.

---

## Overview

The application allows Admin and User roles to manage tasks through a clean and responsive interface. Admin users have full access to all task operations, while normal users can only access tasks assigned to them.

Each task contains the following fields:
- Title
- Description
- Priority
- Status
- Due Date
- Assigned User
- AI Summary
- AI Priority 


---

## Tech Stack

- Laravel 12
- PHP 8.x
- MySQL 
- Vue 3 + Inertia.js
- Tailwind CSS 
- Laravel Breeze for authentication and starter scaffolding 
- REST APIs [file:1]
- AI integration using provider-based service design (OpenAI / Gemini / Claude / mocked fallback) 
- Chart.js for dashboard analytics, if enabled in the project brief implementation. 


---

## Features

- Clean architecture with separation of concerns.
- Repository Pattern with interface and implementation. 
- Service Layer for business logic. 
- AI-generated task summary and AI priority suggestion. 
- Role-based authorization using Laravel Policies. 
- REST API endpoints for task operations. 
- Responsive UI for task list, create/edit, and detail pages. 
- Dashboard analytics.
- Validation using Form Requests.
- API Resources for structured JSON responses

---

## Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/riswanrishaldar/task-ai-manager

```

### 2. Install backend dependencies

```bash
composer install
```

### 3. Install frontend dependencies

```bash
npm install
```

### 4. Create environment file

```bash
cp .env.example .env
```

### 5. Configure environment

Update the `.env` file with your database and application configuration.

Example:

```env
APP_NAME="Laravel AI Task Manager"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_ai
DB_USERNAME=task_user
DB_PASSWORD=

QUEUE_CONNECTION=database

GEMINI_API_KEY=
```

### 6. Generate application key

```bash
php artisan key:generate
```

### 7. Run migrations and seeders

```bash
php artisan migrate --seed
```

### 8. Start queue worker

```bash
php artisan queue:work
```

### 9. Run development servers

```bash
php artisan serve
npm run dev
```

The application will be available at:

```text
http://127.0.0.1:8000
```

---

## Architecture Summary

This project follows a layered architecture to keep the codebase clean, testable, and maintainable. The design was intentionally structured around the machine test’s mandatory requirements. 

### Core architecture decisions

- Controllers are thin and handle only request/response flow.
- Business logic is placed inside `TaskService`. 
- Data persistence is handled through `TaskRepositoryInterface` and `TaskRepository`.
- AI generation is handled by `AIService`. 
- Access control is enforced using Laravel Policies. 
- No direct Eloquent usage is performed inside controllers. 

### Request flow

```text
Request -> Controller -> Service -> Repository -> Model
                            |
                            -> AIService
```

This keeps responsibilities clearly separated:
- Controllers manage HTTP flow.
- Services manage business rules and workflows.
- Repositories manage data access.
- Policies manage authorization.
- AIService manages AI-specific behavior. 

---

## Folder Structure

```text
app/
├── Enums/
│   ├── TaskPriority.php
│   └── TaskStatus.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── TaskController.php
│   │   └── Web/
│   │       └── TaskPageController.php
│   ├── Requests/
│   │   ├── StoreTaskRequest.php
│   │   ├── UpdateTaskRequest.php
│   │   └── UpdateTaskStatusRequest.php
│   └── Resources/
│       └── TaskResource.php
├── Jobs/
│   └── GenerateTaskAISummaryJob.php
├── Models/
│   ├── Task.php
│   └── User.php
├── Policies/
│   └── TaskPolicy.php
├── Providers/
│   └── RepositoryServiceProvider.php
├── Repositories/
│   ├── Contracts/
│   │   └── TaskRepositoryInterface.php
│   └── Eloquent/
│       └── TaskRepository.php
└── Services/
    ├── AIService.php
    └── TaskService.php

resources/
└── js/
    ├── Components/
    ├── Layouts/
    └── Pages/
        ├── Dashboard/
        └── Tasks/

routes/
├── web.php
└── api.php
```

This structure was chosen to align with the mandatory architecture expected in the machine test. [file:1]

---

## Repository Pattern

The Repository Pattern is used to abstract persistence logic away from controllers and services. This ensures that data access is centralized, reusable, and easier to maintain. 

### Why Repository Pattern was used

- To satisfy the machine test requirement. 
- To avoid direct model queries inside controllers. 
- To isolate database access from business logic.
- To make the application easier to test and extend.

### Example responsibilities of `TaskRepositoryInterface`

- Fetch paginated task list with filters.
- Find task by ID.
- Create task.
- Update task.
- Delete task.
- Update task status.
- Eager load relationships and centralize query behavior.

### Binding

Repository binding is registered inside `RepositoryServiceProvider`, allowing the service layer to depend on abstractions instead of concrete implementations. 

---

## Service Layer

The Service Layer handles business logic and coordinates between repositories, policies, transactions, and AI operations. The goal is to keep controllers minimal and keep business workflows in one place.

### `TaskService` responsibilities

- Create task records.
- Update task records.
- Delete tasks.
- Change task status.
- Trigger AI processing.
- Handle transaction boundaries.
- Prepare dashboard metrics.
- Coordinate repository operations.

### Why Service Layer was used

- To keep controllers thin.
- To separate business logic from request handling.
- To avoid duplicated logic across controllers or jobs.
- To make the code easier to test and maintain.

---

## AI Integration

AI integration is implemented through a dedicated `AIService`, as required by the machine test. The AI logic is not called from the controller. Instead, the controller delegates to `TaskService`, which then coordinates with `AIService`. 

### AI responsibilities handled by `AIService`

- Prompt creation. 
- Provider selection.
- API request handling.
- Response parsing. 
- Error handling. 
- Mock fallback support. 

### AI flow

When a task is created or updated:
1. The controller validates the request.
2. The controller forwards the action to `TaskService`.
3. `TaskService` stores or updates the task via repository.
4. `TaskService` triggers AI summary generation directly or through a queued job.
5. `AIService` returns:
   - `ai_summary`
   - `ai_priority`
6. The task is updated through repository methods. 

### Important implementation note

AI is **not** called from the controller. This rule is explicitly required by the machine test and was followed in this implementation. 

### Fallback / mock behavior

To keep the application demo-friendly and avoid failures when real provider keys are not configured, AI generation supports fallback behavior:
- If a valid provider key is present, the configured provider can be used.
- If no provider key is available, the application uses a mocked fallback response.
- If external AI fails, a safe fallback response is returned instead of breaking the task workflow. 

### Example AI output

- `ai_summary`: Short AI-generated summary of the task.
- `ai_priority`: Suggested priority based on title, description, and due date. 

### AI prompt documentation

The prompt logic used by `AIService` is documented in code and can be described as:
- Input: task title, description, due date, assigned user context, and current priority.
- Output: concise summary and recommended priority. [file:1]

---

## Authorization and Policies

Authorization is enforced using Laravel Policies, which is one of the mandatory requirements of the machine test. 

### Role rules

- Admin has full access to all task operations. 
- User can only access tasks assigned to them. 

### Policy responsibilities

`TaskPolicy` is responsible for:
- Viewing a task.
- Updating a task.
- Deleting a task.
- Changing task status.
- Restricting access based on role and assignment. 

### Security approach

Authorization is enforced at both action level and data visibility level:
- Policies protect actions such as view, update, and delete. 
- Repository or query-level filtering ensures normal users only see their own assigned tasks.

This combination helps prevent unauthorized access and keeps the implementation robust. 

---

## Validation

Validation is implemented using Laravel Form Requests as required in the machine test. 

### Form Requests used

- `StoreTaskRequest`
- `UpdateTaskRequest`
- `UpdateTaskStatusRequest`

This keeps validation rules reusable, centralized, and separate from controllers. 

---

## API Resources

JSON responses are structured through Laravel API Resources for consistency and clean API design. The machine test explicitly requires API Resources and proper HTTP responses. 

### Resource used

- `TaskResource`

This ensures a stable API response format and prevents raw model exposure. 

---

## API Endpoints

The application exposes the following REST API endpoints required by the machine test: 

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST   |'/api/tasks/login/'|to login and create token
| GET | `/api/tasks` | List tasks with filters and pagination. |
| POST | `/api/tasks` | Create a new task.  |
| GET | `/api/tasks/{id}` | Get task details. |
| PATCH | `/api/tasks/{id}/status` | Update task status.  |
| GET | `/api/tasks/{id}/ai-summary` | Fetch AI-generated summary and priority.  |

### API notes

- Uses proper HTTP status codes. 
- Uses Form Request validation. 
- Uses API Resources. 
- Protected by authentication and authorization rules.

---

## UI Pages

The UI was implemented based on the machine test’s required screens. 

### Implemented pages

- Task List Page. 
- Task Create / Edit Page. 
- Task Detail Page with AI Summary. 
- Dashboard page with task analytics. 

### UI considerations

- Fully responsive. 
- Proper spacing and visual hierarchy. 
- Tailwind CSS-based styling with no inline CSS. 
- Built to stay close to the provided UI references. 

---

## Dashboard Analytics

The dashboard includes high-level task statistics required by the machine test: 

- Total tasks. 
- Completed tasks.
- Pending tasks. 
- High-priority tasks. 
- Task chart visualization using Chart.js, where implemented. 

---





---

## Bonus Features Completed


Completed bonus features in this submission:

- Queued AI job

- Clean commit history

> Update this section based on the bonus items actually completed in your submission. [file:1]

If only some were completed, replace the list with the exact implemented items.

---



## Demo Credentials

### Admin
- Email: admin@example.com
- Password: password

### User
- Email: arun@example.com 
- Password: password
