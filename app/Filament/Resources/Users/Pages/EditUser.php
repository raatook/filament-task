<?php

namespace App\Filament\Resources\Users\Pages;

use App\Actions\User\UpdateUserAction;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    protected Width|string|null $maxContentWidth = 'full';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => $this->record->id !== auth()->id()),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $updateUserAction = app(UpdateUserAction::class);
        $updateUserAction->execute($record->id, $data);

        return $record->fresh();
    }
}
