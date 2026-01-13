<?php

namespace Tests\Unit\Services;

use App\DataTransferObjects\TaskData;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\ProjectUserAssignmentService;
use App\Services\TaskService;
use Mockery;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_task_successfully()
    {
        // Arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $assignmentService = Mockery::mock(ProjectUserAssignmentService::class);

        $taskData = new TaskData(
            projectId: 1,
            userId: 1,
            title: 'Test Task',
            description: 'Test Description',
            status: TaskStatus::PENDING,
            priority: TaskPriority::HIGH,
            dueDate: now()->addDays(7)
        );

        $expectedTask = new Task([
            'id' => 1,
            'project_id' => 1,
            'user_id' => 1,
            'title' => 'Test Task',
            'status' => TaskStatus::PENDING,
            'priority' => TaskPriority::HIGH,
        ]);

        $taskRepository->shouldReceive('create')
            ->once()
            ->with($taskData->toArray())
            ->andReturn($expectedTask);

        $assignmentService->shouldReceive('assignUserToProject')
            ->once()
            ->with(1, 1);

        $service = new TaskService($taskRepository, $assignmentService);

        // Act
        $result = $service->createTask($taskData);

        // Assert
        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('Test Task', $result->title);
    }

    public function test_update_task_status_successfully()
    {
        // Arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $assignmentService = Mockery::mock(ProjectUserAssignmentService::class);

        $taskRepository->shouldReceive('update')
            ->once()
            ->with(1, ['status' => TaskStatus::DONE->value])
            ->andReturn(true);

        $service = new TaskService($taskRepository, $assignmentService);

        // Act
        $result = $service->updateTaskStatus(1, TaskStatus::DONE);

        // Assert
        $this->assertTrue($result);
    }

    public function test_get_urgent_tasks()
    {
        // Arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $assignmentService = Mockery::mock(ProjectUserAssignmentService::class);

        $urgentTasks = collect([
            new Task(['priority' => TaskPriority::URGENT]),
            new Task(['priority' => TaskPriority::CRITICAL]),
        ]);

        $taskRepository->shouldReceive('findUrgentTasks')
            ->once()
            ->with(10)
            ->andReturn($urgentTasks);

        $service = new TaskService($taskRepository, $assignmentService);

        // Act
        $result = $service->getUrgentTasks(10);

        // Assert
        $this->assertCount(2, $result);
    }

    public function test_get_completion_rate()
    {
        // Arrange
        $taskRepository = Mockery::mock(TaskRepositoryInterface::class);
        $assignmentService = Mockery::mock(ProjectUserAssignmentService::class);

        $taskRepository->shouldReceive('getCompletionRate')
            ->once()
            ->with(1)
            ->andReturn(75.5);

        $service = new TaskService($taskRepository, $assignmentService);

        // Act
        $result = $service->getCompletionRate(1);

        // Assert
        $this->assertEquals(75.5, $result);
    }
}
