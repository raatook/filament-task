<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Actions\Project\CreateProjectAction;
use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $createProjectAction = app(CreateProjectAction::class);
        return $createProjectAction->execute($data);
    }
}
