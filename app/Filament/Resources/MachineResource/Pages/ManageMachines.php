<?php

namespace App\Filament\Resources\MachineResource\Pages;

use App\Filament\Resources\MachineResource;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;

class ManageMachines extends ManageRecords
{
    protected static string $resource = MachineResource::class;

    protected function getHeaderWidgets(): array
    {
        $resource = static::getResource();

        return $resource::getWidgets();
    }

    #[On('saved')]
    public function refresh()
    {
    }
}
