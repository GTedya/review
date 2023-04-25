<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DealerManagerResource\Pages;
use App\Models\User;
use App\Utilities\Helpers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DealerManagerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'dealer_managers';

    protected static ?string $modelLabel = 'Менеджер Дилера';
    protected static ?string $pluralModelLabel = 'Менеджеры Дилера';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                Hidden::make('role')
                    ->saveRelationshipsUsing(function (User $record) {
                        $record->roles()->sync([2]);
                    }),

                Card::make()->schema([
                    SpatieMediaLibraryFileUpload::make('logo')
                        ->image()
                        ->enableOpen()
                        ->label('Лого')
                        ->directory('form-tmp')
                        ->collection('logo')
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
                TextColumn::make('phone')->label('Номер телефона')->sortable(),
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
            'index' => Pages\ListDealerManagers::route('/'),
            'create' => Pages\CreateDealerManager::route('/create'),
            'edit' => Pages\EditDealerManager::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $users = User::where('id', '!=', Auth::id());
        return $users->whereHas('roles', function ($query) {
            $query->where('name', 'dealer_manager');
        });
    }
}
