<?php

namespace App\Filament\Pages;

use App\Filament\Standalone\StandaloneForm;
use App\Models\Transaction;
use App\Services\Transaction\Actions\CreateAction;
use Exception;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class CreateTransaction extends Page implements HasForms
{
    use StandaloneForm;

    protected string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string $view = 'filament.pages.transaction';

    protected static ?string $navigationLabel = 'Transaksi';

    protected ?string $heading = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['date'] ??= now();
        $data['user_id'] ??= auth()->id();

        return $data;
    }

    public function resetForm(): void
    {
        $this->form->fill([
            'date' => now(),
        ]);
    }

    protected function handleSaveRecord(array $data): Model
    {
        return app(CreateAction::class)->handle($data);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1, 'md' => 4])
                ->schema([
                    TextInput::make('customer')
                        ->label('Pelanggan')
                        ->required()
                        ->maxLength(50),
                    TextInput::make('weight')
                        ->label('Berat')
                        ->helperText('Pakaian dalam kilogram (kg)')
                        ->required()
                        ->numeric()
                        ->minValue(1),
                    Select::make('service_id')
                        ->label('Layanan')
                        ->relationship('service', 'name')
                        ->required(),
                ]),
        ];
    }

    protected function getFailedNotification(Exception $exception): ?Notification
    {
        return Notification::make()
            ->danger()
            ->title('Data master mesin mesin lengkap')
            ->title($exception->getMessage());
    }

    protected function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title)
            ->duration(10000)
            ->actions([
                Action::make('receipt')
                    ->label('Tanda Terima')
                    ->url(route('receipt', ['transaction' => $this->getRecord()]))
                    ->openUrlInNewTab()
            ]);
    }
}
