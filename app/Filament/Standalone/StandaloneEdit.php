<?php

namespace App\Filament\Standalone;

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;

trait StandaloneEdit
{
    /**
     * @internal Never override or call this method. If you completely override `fillForm()`, copy the contents of this method into your override.
     *
     * @param  array<string, mixed>  $extraData
     */
    protected function fillFormWithDataAndCallHooks(Model $record, array $extraData = []): void
    {
        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill([
            ...$record->attributesToArray(),
            ...$extraData,
        ]);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    #[On('edit')]
    public function edit(string $id)
    {
        $this->record = $this->resolveRecord($id);

        $this->fillForm();
    }

    protected function resolveRecord(string $id): Model
    {
        $model = $this->getModel();

        return $model::query()->findOrFail($id);
    }
}
