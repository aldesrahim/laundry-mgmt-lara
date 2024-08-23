<?php

namespace App\Filament\Resources\MachineResource\Widgets;

use App\Filament\Resources\MachineResource;
use App\Filament\Widgets\FormWidget;
use App\Models\Machine;
use App\Services\Machine\Actions\CreateAction;
use App\Services\Machine\Actions\UpdateAction;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class MachineForm extends FormWidget
{
    protected string $model = Machine::class;

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
        return MachineResource::getFormSchema();
    }

    protected function getFailedNotification(Exception $exception): ?Notification
    {
        return Notification::make()
            ->danger()
            ->title('Data master mesin mesin lengkap')
            ->title($exception->getMessage());
    }
}
