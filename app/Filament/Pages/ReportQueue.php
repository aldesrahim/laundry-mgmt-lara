<?php

namespace App\Filament\Pages;

use App\Services\Queue\Status;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class ReportQueue extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.report-queue';

    protected static ?string $navigationLabel = 'Laporan Antrean';

    protected ?string $heading = 'Laporan Antrean';

    protected static ?int $navigationSort = 6;

    public array $data = [
        'from_date' => null,
        'until_date' => null,
        'status' => Status::All,
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
                Select::make('status')
                    ->options(Status::class)
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

        return redirect()->route('report', ['driver' => 'queue', ...$state]);
    }
}
