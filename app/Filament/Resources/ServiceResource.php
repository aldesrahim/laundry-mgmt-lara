<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use App\Services\Service\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Master Layanan';

    protected static ?string $modelLabel = 'Master Layanan';

    protected static ?int $navigationSort = 4;

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(['default' => 1, 'md' => 5])
                ->schema([
                    Forms\Components\TextInput::make('id')
                        ->label('ID')
                        ->disabled(),
                    Forms\Components\TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(50),
                    Forms\Components\TextInput::make('duration')
                        ->label('Durasi')
                        ->minValue(1)
                        ->helperText('Pengerjaan dalam hari')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('price')
                        ->label('Harga')
                        ->minValue(1)
                        ->required()
                        ->numeric()
                        ->prefix('Rp')
                ]),
            Forms\Components\CheckboxList::make('machines')
                ->label('Mesin')
                ->relationship('machines', 'type')
                ->minItems(1),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(
            static::getFormSchema()
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->using(function (Service $record) {
                        try {
                            return app(DeleteAction::class)->handle($record);
                        } catch (\Throwable $exception) {
                            Notification::make()
                                ->danger()
                                ->title($exception->getMessage())
                                ->send();
                        }

                        return false;
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
