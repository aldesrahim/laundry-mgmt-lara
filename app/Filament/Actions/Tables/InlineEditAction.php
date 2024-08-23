<?php

namespace App\Filament\Actions\Tables;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InlineEditAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'edit';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions::edit.single.label'));

        $this->icon('heroicon-s-pencil-square');

        $this->dispatch('edit', fn () => $this->process(fn (array $data, Model $record, Table $table) => [$record->getKey()]));
    }
}
