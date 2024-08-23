<?php

namespace App\Filament\Resources;

use App\Filament\Actions\Tables\InlineDeleteAction;
use App\Filament\Actions\Tables\InlineEditAction;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Widgets\UserForm;
use App\Models\User;
use App\Services\User\Actions\DeleteAction;
use App\Services\User\Level;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Master Pengguna';

    protected static ?string $modelLabel = 'Master Pengguna';

    protected static ?int $navigationSort = 5;

    public static function getFormSchema()
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
                    Forms\Components\TextInput::make('username')
                        ->required()
                        ->maxLength(50),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->maxLength(200),
                    Forms\Components\Select::make('level')
                        ->required()
                        ->options(Level::class),
                ]),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(
            static::getFormSchema(),
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->searchable(),
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
                InlineDeleteAction::make()->using(fn (User $record) => app(DeleteAction::class)->handle($record)),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            UserForm::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
