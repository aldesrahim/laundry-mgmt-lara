<?php

namespace App\Filament\Actions\Tables;

use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class InlineDeleteAction extends DeleteAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (): void {
            try {
                $result = $this->process(static fn (Model $record) => $record->delete());

                if (!$result) {
                    $this->failure();

                    return;
                }

                $this->success();
            } catch (Throwable $exception) {
                Notification::make()
                    ->danger()
                    ->title($exception->getMessage())
                    ->send();
            }
        });
    }
}
