<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource;
use App\Filament\Widgets\FormWidget;
use App\Models\User;
use App\Services\User\Actions\CreateAction;
use App\Services\User\Actions\UpdateAction;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class UserForm extends FormWidget
{
    protected string $model = User::class;

    protected function handleSaveRecord(array $data): Model
    {
        $record = $this->getRecord();

        if (blank($record)) {
            return app(CreateAction::class)->handle($data);
        }

        return app(UpdateAction::class)->handle($record, $data);
    }

    protected function getFormSchema(): array
    {
        return UserResource::getFormSchema();
    }

    protected function getFailedNotification(Exception $exception): ?Notification
    {
        return Notification::make()
            ->danger()
            ->title('Data master mesin pengguna lengkap')
            ->body($exception->getMessage());
    }
}
