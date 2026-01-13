<?php

namespace Tests\Unit\Repositories;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected TaskRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TaskRepository();
    }

    public function test_find_by_id_returns_task()
    {
        // Arrange
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);

        // Act
        $result = $this->repository->findById($task->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($task->id, $result->id);
    }

    public function test_find_by_status_returns_correct_tasks()
    {
        // Arrange
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Task::factory()->count(3)->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'status' => TaskStatus::PENDING,
        ]);

        Task::factory()->count(2)->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'status' => TaskStatus::DONE,
        ]);

        // Act
        $result = $this->repository->findByStatus(TaskStatus::PENDING);

        // Assert
        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($task) => $task->status === TaskStatus::PENDING));
    }

    public function test_create_task_successfully()
    {
        // Arrange
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $data = [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'title' => 'New Task',
            'description' => 'Task Description',
            'status' => TaskStatus::PENDING->value,
            'priority' => 1,
        ];

        // Act
        $result = $this->repository->create($data);

        // Assert
        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('New Task', $result->title);
        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_count_by_status_returns_correct_count()
    {
        // Arrange
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Task::factory()->count(5)->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'status' => TaskStatus::DONE,
        ]);

        // Act
        $result = $this->repository->countByStatus(TaskStatus::DONE);

        // Assert
        $this->assertEquals(5, $result);
    }

    public function test_get_completion_rate_calculates_correctly()
    {
        // Arrange
        $user = User::factory()->create();
        $project = Project::factory()->create();

        // Create 10 tasks, 7 completed
        Task::factory()->count(7)->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'status' => TaskStatus::DONE,
        ]);

        Task::factory()->count(3)->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'status' => TaskStatus::PENDING,
        ]);

        // Act
        $result = $this->repository->getCompletionRate();

        // Assert
        $this->assertEquals(70.0, $result);
    }
}
