<?php

namespace App\Filament\Standalone;

use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Support\Enums\Alignment;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Throwable;

trait StandaloneForm
{
    use CanUseDatabaseTransactions;
    use HasUnsavedDataChangesAlert;
    use InteractsWithActions;
    use InteractsWithFormActions;
    use InteractsWithForms;

    public ?Model $record = null;

    public array $data = [];

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function mount()
    {
        $this->fillForm();
    }

    public function getRecord(): ?Model
    {
        return $this->record;
    }

    public function areFormActionsSticky(): bool
    {
        return false;
    }

    public function getFormActionsAlignment(): string|Alignment
    {
        return Alignment::Start;
    }

    public function resetForm(): void
    {
        $this->record = null;

        $this->fillForm();
    }

    protected function getFormModel(): Model|string|null
    {
        return $this->getModel();
    }

    public function fillForm(): void
    {
        $this->form->fill();

        if ($this->record && method_exists(static::class, 'fillFormWithDataAndCallHooks')) {
            $this->fillFormWithDataAndCallHooks($this->record);
        }
    }

    /**
     * @return class-string<Model>
     */
    public function getModel(): string
    {
        return $this->model;
    }

    protected function callHook(string $hook): void
    {
        if (!method_exists($this, $hook)) {
            return;
        }

        $this->{$hook}();
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->record = $this->handleSaveRecord($data);

            $this->form->model($this->getRecord())->saveRelationships();

            $this->callHook('afterSave');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            if ($notification = $this->getFailedNotification($exception)) {
                $notification->send();

                return;
            }

            throw $exception;
        }

        $this->rememberData();

        $this->getSavedNotification()?->send();

        $this->dispatch('saved');

        $this->resetForm();
    }

    /**
     * @param array<string, mixed> $data
     */
    abstract protected function handleSaveRecord(array $data): Model;

    protected function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data berhasil disimpan';
    }

    protected function getFailedNotification(Exception $exception): ?Notification
    {
        return null;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Simpan')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batal')
            ->action('resetForm')
            ->color('gray');
    }
}
