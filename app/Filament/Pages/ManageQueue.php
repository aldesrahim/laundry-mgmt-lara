<?php

namespace App\Filament\Pages;

use App\Models\Queue;
use App\Services\Queue\Actions\GenerateAction;
use App\Services\Queue\Status;
use Filament\Actions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Throwable;

class ManageQueue extends Page implements HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static string $view = 'filament.pages.manage-queue';

    protected static ?string $navigationLabel = 'Antrean';

    protected ?string $heading = 'Antrean';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Queue::query()
                    ->with('transaction.service')
                    ->orderBy('batch')
                    ->orderBy('order'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('transaction.customer')
                    ->label('Pelanggan'),
                Tables\Columns\TextColumn::make('transaction.weight')
                    ->alignCenter()
                    ->label('Berat (kg)')
                    ->numeric(),
                Tables\Columns\TextColumn::make('transaction.service.name')
                    ->label('Layanan'),
                Tables\Columns\TextColumn::make('transaction.date')
                    ->label('Tanggal Masuk')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('transaction.target_date')
                    ->label('Target Selesai')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('finish')
                    ->label('Selesai')
                    ->color('success')
                    ->icon('heroicon-s-check')
                    ->requiresConfirmation()
                    ->action(fn (Queue $record) => $record->markAsFinished())
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('finished')
                    ->label('Status')
                    ->default(false)
                    ->nullable()
                    ->placeholder(Status::All->getLabel())
                    ->trueLabel(Status::Finished->getLabel())
                    ->falseLabel(Status::NotFinished->getLabel())
                    ->queries(
                        true: fn (Builder $query) => $query->where('finished', 1),
                        false: fn (Builder $query) => $query->where('finished', 0),
                        blank: fn (Builder $query) => $query,
                    ),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate')
                ->action('generate')
        ];
    }

    public function generate()
    {
        try {
            DB::beginTransaction();

            app(GenerateAction::class)->handle(
                auth()->user()
            );

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();

            return;
        }

        Notification::make()
            ->color('success')
            ->title('Generate antrean berhasil')
            ->send();
    }
}
