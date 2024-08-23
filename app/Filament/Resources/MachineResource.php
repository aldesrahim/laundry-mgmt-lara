<?php

namespace App\Filament\Resources;

use App\Filament\Actions\Tables\InlineDeleteAction;
use App\Filament\Actions\Tables\InlineEditAction;
use App\Filament\Resources\MachineResource\Pages;
use App\Filament\Resources\MachineResource\Widgets\MachineForm;
use App\Models\Machine;
use App\Services\Machine\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MachineResource extends Resource
{
    protected static ?string $model = Machine::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $navigationLabel = 'Master Mesin';

    protected static ?string $modelLabel = 'Master Mesin';

    protected static ?int $navigationSort = 3;

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(['default' => 1, 'md' => 5])
                ->schema([
                    Forms\Components\TextInput::make('id')
                        ->label('ID')
                        ->disabled(),
                    Forms\Components\TextInput::make('type')
                        ->label('Jenis')
                        ->required()
                        ->maxLength(50),
                    Forms\Components\TextInput::make('capacity')
                        ->label('Kapasitas')
                        ->helperText('Maksimal dalam kilogram (kg)')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('duration')
                        ->label('Durasi')
                        ->helperText('Per kapasitas maksismal dalam menit')
                        ->required()
                        ->numeric(),
                ])
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
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi')
                    ->alignCenter()
                    ->numeric()
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
                InlineEditAction::make(),
                InlineDeleteAction::make()
                    ->using(fn (Machine $record) => app(DeleteAction::class)->handle($record)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMachines::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            MachineForm::class,
        ];
    }
}
