<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

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
