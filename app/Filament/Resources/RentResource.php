<?php

namespace App\Filament\Resources;

use App\Constants\RentTypeConstants;
use App\Filament\Resources\RentResource\Pages;
use App\Models\Geo;
use App\Models\Rent;
use App\Models\RentVehicle;
use App\Models\VehicleType;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class RentResource extends Resource
{
    protected static ?string $model = Rent::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    protected static ?string $modelLabel = 'Объявление аренды';

    protected static ?string $pluralModelLabel = 'Объявления аренды';


    public static function form(Form $form): Form
    {
        return $form->columns(3)->schema([
            Grid::make()->columnSpan(2)->schema([

                Card::make()->schema([
                    Grid::make()->schema([
                        TextInput::make('name')
                            ->label('Заголовок')
                            ->required(),

                        TextInput::make('phone')
                            ->label('Номер телефона')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email(),

                        Select::make('type')
                            ->label('Тип')
                            ->options(RentTypeConstants::RENT_TYPES)
                            ->required(),
                    ]),
                    Textarea::make('text')
                        ->label('Текст')
                        ->required(),

                    Fieldset::make('Изображение')->columns(1)->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->image()
                            ->disableLabel()
                            ->multiple()
                            ->collection('images')
                            ->label('Изображение')
                            ->directory('form-tmp')
                    ]),

                ]),
                Section::make('Транспортные средства')->schema([
                    Repeater::make('rent_vehicles')
                        ->visibleOn('edit')
                        ->label('Транспортные средства')
                        ->createItemButtonLabel('Добавить')
                        ->relationship('rentVehicles')
                        ->defaultItems(0)
                        ->schema([
                            Select::make('type_id')
                                ->required()
                                ->label('Выберите тип ТС')
                                ->relationship(
                                    'vehicleTypes',
                                    'name',
                                    function (Builder $query, ?RentVehicle $record) {
                                        $query->withTrashed()
                                            ->where('deleted_at', null)
                                            ->orWhere('id', $record?->type_id);
                                    }
                                )
                                ->getOptionLabelFromRecordUsing(function (VehicleType $record) {
                                    return $record->trashed() ? "{$record->name} (Тип ТС удален)" : $record->name;
                                })
                                ->afterStateHydrated(function (?RentVehicle $record, $set) {
                                    $set('type', $record?->rent_id);
                                }),
                        ])
                ])->visibleOn('edit')->collapsible(),
            ]),

            Grid::make()->columnSpan(1)->schema([
                Card::make()->schema([
                    Select::make('user_id')
                        ->label('Пользователь')
                        ->required()
                        ->disabledOn('edit')
                        ->relationship('user', 'name', function ($query) {
                            $query->whereHas('roles', fn($query) => $query->where('name', 'client'));
                        }),

                    Select::make('geo_id')
                        ->label('Область')
                        ->required()
                        ->relationship('geo', 'name', function (Builder $query, ?Rent $record) {
                            $query->withTrashed()->where('deleted_at', null)->orWhere('id', $record?->geo_id);
                        })
                        ->getOptionLabelFromRecordUsing(function (Geo $record) {
                            return $record->trashed() ? "{$record->name} (область удалена)" : $record->name;
                        }),

                    DateTimePicker::make('created_at')
                        ->label('Дата создания')
                        ->default('now')
                        ->displayFormat('Y-m-d H:i:s'),
                ]),

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('ФИО')->sortable()->searchable(),
                TextColumn::make('phone')->label('Номер телефона')->sortable(),
                TextColumn::make('type')->label('Тип объявления')->sortable(),
                TextColumn::make('geo.name')->label('Область')
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('geo', fn(Builder $q) => $q->withTrashed()->where('name', 'like', "%$search%")
                        );
                    })
                    ->getStateUsing(fn(Rent $record) => $record->geo()->withTrashed()->first()?->name ?? ''),
                TextColumn::make('created_at')->label('Дата создания')->sortable(),
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
            'index' => Pages\ListRents::route('/'),
            'create' => Pages\CreateRent::route('/create'),
            'edit' => Pages\EditRent::route('/{record}/edit'),
        ];
    }
}
