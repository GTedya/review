<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Utilities\Helpers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Пользователь';
    protected static ?string $pluralModelLabel = 'Пользователи';

    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Grid::make()->columnSpan(2)->schema([
                Card::make()->schema([
                    TextInput::make('name')
                        ->label('Имя')
                        ->required(),

                    TextInput::make('phone')
                        ->label('Номер телефона')
                        ->required()
                        ->minLength(10)
                        ->dehydrateStateUsing(function ($state) {
                            return Helpers::getCleanPhone($state);
                        }),

                    TextInput::make('email')
                        ->label('Email')
                        ->required(),

                    TextInput::make('password')
                        ->password()
                        ->required(fn($context) => $context === 'create')
                        ->dehydrated(fn($context, $state) => $context !== 'edit' || filled($state))
                        ->minLength(8)
                        ->label('Пароль')
                        ->dehydrateStateUsing(function (string $state) {
                            return Hash::make($state);
                        }),
                ]),
            ]),

            Grid::make()->columnSpan(1)->schema([
                Card::make()->schema([
                    Select::make('role')
                        ->label('Роль пользователя')
                        ->reactive()
                        ->options(function () {
                            return Role::query()->get()->pluck('name', 'id')->map(fn($name) => User::ROLE_NAMES[$name]);
                        })
                        ->saveRelationshipsUsing(function (User $record, $state) {
                            $record->roles()->sync($state);
                        })

                        ->afterStateHydrated(function (?User $record, $set) {
                            $set('role', $record?->roles()->first()?->id);
                        })
                        ->required(),

                    Fieldset::make('Лого')->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->image()
                            ->enableOpen()
                            ->disableLabel()
                            ->directory('form-tmp')
                            ->collection('logo')
                            ->panelLayout('integrated'),
                    ])->visible(function ($get) {
                        return ($get('role') == '2') || ($get('role') == '3');
                    }),
                ]),
            ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Имя')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('roles.name')->label('Роль')->sortable(),
                TextColumn::make('created_at')->label('Создан')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return User::where('id', '!=', Auth::id());
    }
}
