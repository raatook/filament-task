<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Actions\Task\CreateTaskAction;
use App\Filament\Resources\Tasks\TaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! auth()->user()->isAdmin()) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $createTaskAction = app(CreateTaskAction::class);
        return $createTaskAction->execute($data);
    }
}
