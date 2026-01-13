<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Actions\Project\UpdateProjectAction;
use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $updateProjectAction = app(UpdateProjectAction::class);
        $updateProjectAction->execute($record->id, $data);

        return $record->fresh();
    }
}
