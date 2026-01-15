<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Actions\Task\UpdateTaskAction;
use App\Actions\Task\UpdateTaskStatusAction;
use App\Enums\TaskStatus;
use App\Filament\Resources\Tasks\TaskResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => auth()->user()->isAdmin()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! auth()->user()->isAdmin()) {
            $this->form->getComponent('user_id')?->disabled(true);
            $this->form->getComponent('project_id')?->disabled(true);
            $this->form->getComponent('title')?->disabled(true);
            $this->form->getComponent('description')?->disabled(true);
            $this->form->getComponent('priority')?->disabled(true);
            $this->form->getComponent('due_date')?->disabled(true);
        }

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        if (! auth()->user()->isAdmin()) {
            $updateStatusAction = app(UpdateTaskStatusAction::class);

            $status = $data['status'];

            if (!$status instanceof TaskStatus) {
                $status = TaskStatus::from($data['status']);
            }

            $updateStatusAction->execute($record->id, $status);
        } else {
            // Pour les admins, mise à jour complète
            $updateTaskAction = app(UpdateTaskAction::class);
            $updateTaskAction->execute($record->id, $data);
        }

        return $record->fresh();
    }
}
