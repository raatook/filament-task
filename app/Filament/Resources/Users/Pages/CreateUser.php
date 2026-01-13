<?php

namespace App\Filament\Resources\Users\Pages;

use App\Actions\User\CreateUserAction;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function __construct(
        private readonly CreateUserAction $createUserAction
    ) {
        parent::__construct();
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->createUserAction->execute($data);
    }
}
