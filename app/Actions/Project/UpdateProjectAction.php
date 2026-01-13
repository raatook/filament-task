<?php

namespace App\Actions\Project;

use App\DataTransferObjects\ProjectData;
use App\Services\ProjectService;

class UpdateProjectAction
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {}

    public function execute(int $projectId, array $data): bool
    {
        $projectData = ProjectData::fromArray($data);

        return $this->projectService->updateProject($projectId, $projectData);
    }
}
