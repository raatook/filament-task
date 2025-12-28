<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use App\Models\Project;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! auth()->user()->isAdmin()) {
            $data['user_id'] = auth()->id();
        }

        if (auth()->user()->isAdmin() && isset($data['user_id']) && isset($data['project_id'])) {
            $project = Project::withoutGlobalScopes()->find($data['project_id']);
            $userId = $data['user_id'];

            if ($project && ! $project->users()->where('users.id', $userId)->exists()) {
                $project->users()->attach($userId);
            }
        }

        return $data;
    }
}
