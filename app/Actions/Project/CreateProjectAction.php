<?php

namespace App\Actions\Project;

use App\DataTransferObjects\ProjectData;
use App\Models\Project;
use App\Services\ProjectService;

class CreateProjectAction
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {}

    public function execute(array $data): Project
    {
        $projectData = ProjectData::fromArray($data);

        return $this->projectService->createProject($projectData);
    }
}
