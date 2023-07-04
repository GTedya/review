<?php

namespace App\Filament\BaseResource;

use App\Models\Geo;
use App\Models\User;
use App\Utilities\Helpers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Phpsa\FilamentPasswordReveal\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $role;
    protected static ?int $role_id;

    protected static bool $hasLogo = false;

    public static function baseFields(): array
    {
        return

            [
                Grid::make()->columnSpan(2)->schema([
                    Card::make()->columns()->schema([
                        TextInput::make('name')
                            ->label('ФИО')
                            ->required(),

                        TextInput::make('phone')
                            ->label('Номер телефона')
                            ->required()
                            ->minLength(10)
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(function ($state) {
                                return Helpers::getCleanPhone($state);
                            }),

                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('inn')
                            ->label('ИНН')
                            ->required(),

                        TextInput::make('org_name')
                            ->label('Название организации'),

                        TextInput::make('org_type')
                            ->label('Тип организации'),

                        Select::make('geo_id')
                            ->label('Область')
                            ->relationship('geo', 'name', function (Builder $query, ?User $record) {
                                $query->doesntHave('children')->withTrashed()->where('deleted_at', null)->orWhere(
                                    'id',
                                    $record?->geo_id
                                );
                            })
                            ->getOptionLabelFromRecordUsing(function (Geo $record) {
                                return $record->trashed() ? "{$record->name} (область удалена)" : $record->name;
                            }),

                        Password::make('password')
                            ->required(fn($context) => $context === 'create')
                            ->dehydrated(fn($context, $state) => $context !== 'edit' || filled($state))
                            ->minLength(8)
                            ->label('Пароль')
                            ->dehydrateStateUsing(function (string $state) {
                                return Hash::make($state);
                            }),
                    ]),
                    Hidden::make('role')
                        ->saveRelationshipsUsing(function (User $record) {
                            $record->roles()->sync([static::$role_id]);
                        }),
                ]),
                Grid::make()->columnSpan(1)->schema([
                    Card::make()->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->image()
                            ->enableOpen()
                            ->label('Лого')
                            ->directory('form-tmp')
                            ->collection('logo')
                    ]),
                ])->visible(static::$hasLogo),
            ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('ФИО')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Номер телефона')->sortable(),
                TextColumn::make('created_at')->label('Создан')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $users = User::where('id', '!=', Auth::id());
        return $users->whereHas('roles', function ($query) {
            $query->where('name', static::$role);
        });
    }


}
