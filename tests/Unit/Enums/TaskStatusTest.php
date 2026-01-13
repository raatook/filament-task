<?php

namespace Tests\Unit\Enums;

use App\Enums\TaskStatus;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    public function test_task_status_values_are_correct()
    {
        $this->assertEquals('pending', TaskStatus::PENDING->value);
        $this->assertEquals('in_progress', TaskStatus::IN_PROGRESS->value);
        $this->assertEquals('done', TaskStatus::DONE->value);
    }

    public function test_task_status_labels_are_translated()
    {
        $this->assertIsString(TaskStatus::PENDING->label());
        $this->assertIsString(TaskStatus::IN_PROGRESS->label());
        $this->assertIsString(TaskStatus::DONE->label());
    }

    public function test_task_status_colors_are_correct()
    {
        $this->assertEquals('gray', TaskStatus::PENDING->color());
        $this->assertEquals('warning', TaskStatus::IN_PROGRESS->color());
        $this->assertEquals('success', TaskStatus::DONE->color());
    }

    public function test_task_status_options_returns_array()
    {
        $options = TaskStatus::options();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('pending', $options);
        $this->assertArrayHasKey('in_progress', $options);
        $this->assertArrayHasKey('done', $options);
    }

    public function test_task_status_can_be_created_from_value()
    {
        $status = TaskStatus::from('pending');

        $this->assertInstanceOf(TaskStatus::class, $status);
        $this->assertEquals(TaskStatus::PENDING, $status);
    }
}
