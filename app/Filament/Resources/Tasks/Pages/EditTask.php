<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use App\Models\Project;
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
        // Simple users can only edit status
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! auth()->user()->isAdmin()) {
            $original = $this->record->toArray();

            $data = array_merge($original, [
                'status' => $data['status'],
            ]);
        } else {
            if (isset($data['user_id']) && isset($data['project_id'])) {
                $project = Project::withoutGlobalScopes()->find($data['project_id']);
                $userId = $data['user_id'];

                if ($project && ! $project->users()->where('users.id', $userId)->exists()) {
                    $project->users()->attach($userId);
                }
            }
        }

        return $data;
    }
}
