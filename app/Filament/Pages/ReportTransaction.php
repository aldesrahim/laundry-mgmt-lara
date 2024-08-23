<?php

namespace App\Filament\Pages;

use App\Services\ReportTemplate\Drivers\ReportTransaction as ReportTransactionTemplate;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class ReportTransaction extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.report-transaction';

    protected static ?string $navigationLabel = 'Laporan Transaksi';

    protected ?string $heading = 'Laporan Transaksi';

    protected static ?int $navigationSort = 7;

    public array $data = [
        'from_date' => null,
        'until_date' => null,
    ];

    public function mount()
    {
        $this->data['from_date'] = now()->startOfMonth()->toDateString();
        $this->data['until_date'] = now()->endOfMonth()->toDateString();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                DatePicker::make('from_date')
                    ->label('Dari Tanggal')
                    ->date()
                    ->required(),
                DatePicker::make('until_date')
                    ->label('Sampai Tanggal')
                    ->date()
                    ->required(),
            ])
            ->columns(['default' => 1, 'md' => 2, 'lg' => 4]);
    }

    public function getFormActions(): array
    {
        return [
            Action::make('show')
                ->label('Lihat Laporan')
                ->action('show')
        ];
    }

    public function show()
    {
        $state = $this->form->getState();

        $exists = ReportTransactionTemplate::getQuery($state)->exists();

        if (!$exists) {
            Notification::make()
                ->danger()
                ->title('Tidak ada data')
                ->send();

            return;
        }

        redirect()->route('report', ['driver' => 'transaction', ...$state]);
    }
}
