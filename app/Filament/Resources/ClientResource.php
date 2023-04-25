<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\User;
use App\Utilities\Helpers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

class ClientResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'clients';

    protected static ?string $modelLabel = 'Клиент';
    protected static ?string $pluralModelLabel = 'Клиенты';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make()->schema([
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

                    Hidden::make('role')
                        ->saveRelationshipsUsing(function (User $record) {
                            $record->roles()->sync([4]);
                        }),
                ]),

                Section::make('Файлы')->collapsed()->schema([
                    Repeater::make('files')
                        ->disableLabel()
                        ->disableItemMovement()
                        ->relationship('files')
                        ->createItemButtonLabel('Добавить')
                        ->schema([
                            Select::make('type_id')
                                ->label('Тип файлов')
                                ->relationship('type', 'name', fn($query) => $query->orderBy('id'))
                                ->required(),

                            SpatieMediaLibraryFileUpload::make('files')
                                ->label('Файлы')
                                ->collection('default')
                                ->directory('form-tmp')
                                ->enableDownload()
                                ->enableOpen()
                                ->multiple()
                                ->required(),
                        ])
                        ->rules([
                            function () {
                                return function (string $attribute, $value, callable $fail) {
                                    $types = [];
                                    foreach ($value as $item) {
                                        $id = $item['type_id'];
                                        if ($types[$id] ?? false) {
                                            return $fail(
                                                'Один или более тип файлов повторяется. Перепроверьте указанные значения.'
                                            );
                                        }
                                        $types[$id] = true;
                                    }
                                    return true;
                                };
                            }
                        ]),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $users = User::where('id', '!=', Auth::id());
        return $users->whereHas('roles', function ($query) {
            $query->where('name', 'client');
        });
    }
}
